<?php

class EmailNotification {
    private PDO $pdo;
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;
    private $fromEmail;
    private $fromName;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->createTablesIfNotExist();
        
        $this->smtpHost = getenv('SMTP_HOST') ?: 'mailhog';
        $this->smtpPort = getenv('SMTP_PORT') ?: 1025;
        $this->smtpUsername = getenv('SMTP_USERNAME') ?: '';
        $this->smtpPassword = getenv('SMTP_PASSWORD') ?: '';
        $this->fromEmail = getenv('FROM_EMAIL') ?: 'notifications@manegepark.com';
        $this->fromName = getenv('FROM_NAME') ?: 'ManegePark Notifications';
    }

    private function createTablesIfNotExist() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS email_notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                event_type VARCHAR(50) NOT NULL,
                is_enabled BOOLEAN DEFAULT TRUE,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE KEY unique_user_event (user_id, event_type)
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS email_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                event_type VARCHAR(50) NOT NULL,
                email_to VARCHAR(255) NOT NULL,
                subject VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                status VARCHAR(20) NOT NULL,
                error_message TEXT NULL,
                sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )
        ");
    }

    public function getUserNotificationSettings($userId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM email_notifications
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateNotificationSetting($userId, $eventType, $isEnabled) {
        $stmt = $this->pdo->prepare("
            SELECT id FROM email_notifications
            WHERE user_id = :user_id AND event_type = :event_type
        ");
        $stmt->execute([
            'user_id' => $userId,
            'event_type' => $eventType
        ]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $stmt = $this->pdo->prepare("
                UPDATE email_notifications
                SET is_enabled = :is_enabled
                WHERE id = :id
            ");
            return $stmt->execute([
                'is_enabled' => $isEnabled ? 1 : 0,
                'id' => $existing['id']
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO email_notifications (user_id, event_type, is_enabled)
                VALUES (:user_id, :event_type, :is_enabled)
            ");
            return $stmt->execute([
                'user_id' => $userId,
                'event_type' => $eventType,
                'is_enabled' => $isEnabled ? 1 : 0
            ]);
        }
    }

    public function shouldSendNotification($userId, $eventType) {
        $stmt = $this->pdo->prepare("
            SELECT is_enabled FROM email_notifications
            WHERE user_id = :user_id AND event_type = :event_type
        ");
        $stmt->execute([
            'user_id' => $userId,
            'event_type' => $eventType
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            // Default behavior if setting not found: enable for critical alerts, disable for others
            $defaultEnabledEvents = ['system_alert', 'motor_speed_changed_critical']; // Example
            return in_array($eventType, $defaultEnabledEvents);
        }
        
        return (bool)$result['is_enabled'];
    }

    public function getUserEmail($userId) {
        $stmt = $this->pdo->prepare("SELECT email FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['email'] : null;
    }
    
    public function sendPasswordResetEmail($userId, $userEmail, $resetLink) {
        $subject = "Réinitialisation de votre mot de passe ManegePark";
        $message = "
            <h2>Réinitialisation de mot de passe</h2>
            <p>Bonjour,</p>
            <p>Vous avez demandé une réinitialisation de mot de passe pour votre compte ManegePark.</p>
            <p>Veuillez cliquer sur le lien ci-dessous pour créer un nouveau mot de passe :</p>
            <p><a href=\"{$resetLink}\" class=\"btn\">Réinitialiser le mot de passe</a></p>
            <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
            <p>Ce lien expirera dans 1 heure.</p>
            <p>Cordialement,<br>L'équipe ManegePark</p>
        ";
        return $this->sendEmail($userEmail, $subject, $message, 'password_reset', $userId);
    }


    public function sendNotification($userId, $eventType, $subject, $message) {
        if (!$this->shouldSendNotification($userId, $eventType)) {
            return false;
        }
        
        $toEmail = $this->getUserEmail($userId);
        if (!$toEmail) {
            $this->logEmail($userId, $eventType, 'unknown@example.com', $subject, $message, 'failure_missing_email', 'User email not found');
            return false;
        }
        
        return $this->sendEmail($toEmail, $subject, $message, $eventType, $userId);
    }
    
    private function sendEmail($toEmail, $subject, $htmlContent, $eventType, $userId = null) {
        $formattedMessage = $this->formatEmailMessage($htmlContent);
        $success = false;
        $errorMessage = '';
        
        try {
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: {$this->fromName} <{$this->fromEmail}>\r\n";
            $headers .= "Reply-To: {$this->fromEmail}\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            
            $success = mail($toEmail, $subject, $formattedMessage, $headers);
            
            if (!$success) {
                $errorMessage = 'PHP mail() function failed. Check SMTP server configuration and logs.';
            }
        } catch (Exception $e) {
            $errorMessage = 'Exception during email sending: ' . $e->getMessage();
        }
        
        $this->logEmail($userId, $eventType, $toEmail, $subject, $htmlContent, $success ? 'success' : 'failure', $errorMessage);
        return $success;
    }

    public function sendAlertNotification($userId, $eventType, $details) {
        $eventTitles = [
            'motor_speed_changed' => 'La vitesse du moteur a changé',
            'system_alert' => 'Alerte système'
            // Add more or remove as needed
        ];
        
        $title = $eventTitles[$eventType] ?? 'Notification ManegePark';
        $subject = 'ManegePark - ' . $title;
        
        $message = '
            <h2>' . htmlspecialchars($title) . '</h2>
            <p>Une notification importante concernant votre manège :</p>
            <p><strong>' . htmlspecialchars($details) . '</strong></p>
            <p>Date et heure : ' . date('d/m/Y H:i:s') . '</p>
            <p>Veuillez vous connecter au tableau de bord pour plus de détails.</p>
            <p><a href="http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost:8080') . '/dashboard" class="btn">Accéder au tableau de bord</a></p>
        ';
        
        return $this->sendNotification($userId, $eventType, $subject, $message);
    }
    
    private function formatEmailMessage($messageContent) {
        return '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>ManegePark Notification</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 20px auto; padding: 20px; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 5px; }
                .header { background: linear-gradient(135deg, #FF6B00, #0066CC); color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; margin: -20px -20px 20px; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
                .btn { display: inline-block; background-color: #FF6B00; color: white !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
                h2 { color: #FF6B00; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header"><h1>ManegePark</h1></div>
                ' . $messageContent . '
                <div class="footer">
                    <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
                    <p>© ' . date('Y') . ' ManegePark. Tous droits réservés.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function logEmail($userId, $eventType, $toEmail, $subject, $message, $status, $errorMessage = '') {
        $stmt = $this->pdo->prepare("
            INSERT INTO email_logs (user_id, event_type, email_to, subject, message, status, error_message)
            VALUES (:user_id, :event_type, :email_to, :subject, :message, :status, :error_message)
        ");
        
        return $stmt->execute([
            'user_id' => $userId, // Can be null if not user-specific (e.g. system email)
            'event_type' => $eventType,
            'email_to' => $toEmail,
            'subject' => $subject,
            'message' => $message, // Store the raw HTML content passed to sendEmail
            'status' => $status,
            'error_message' => $errorMessage
        ]);
    }

    public function getEmailLogs($userId = null, $limit = 50) {
        $sql = "
            SELECT el.*, u.username 
            FROM email_logs el
            LEFT JOIN users u ON el.user_id = u.id
        "; // LEFT JOIN in case user_id is NULL for some logs
        $params = [];
        
        if ($userId) {
            $sql .= " WHERE el.user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $sql .= " ORDER BY el.sent_at DESC LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        
        if ($userId) {
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}