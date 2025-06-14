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
        
        // Configuration SMTP
        $this->smtpHost = getenv('SMTP_HOST') ?: 'mailhog';
        $this->smtpPort = getenv('SMTP_PORT') ?: 1025;
        $this->smtpUsername = getenv('SMTP_USERNAME') ?: '';
        $this->smtpPassword = getenv('SMTP_PASSWORD') ?: '';
        $this->fromEmail = getenv('FROM_EMAIL') ?: 'notifications@manegepark.com';
        $this->fromName = getenv('FROM_NAME') ?: 'ManegePark Notifications';
    }

    private function createTablesIfNotExist() {
        // Création de la table pour stocker les configurations de notifications
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

        // Création de la table pour stocker l'historique des emails envoyés
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS email_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                event_type VARCHAR(50) NOT NULL,
                email_to VARCHAR(255) NOT NULL,
                subject VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                status VARCHAR(20) NOT NULL,
                error_message TEXT NULL,
                sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
        // Vérifier si le paramètre existe déjà
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
            // Mettre à jour le paramètre existant
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
            // Créer un nouveau paramètre
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
        
        // Si aucun paramètre n'est trouvé, retourner true par défaut
        if (!$result) {
            return true;
        }
        
        return (bool)$result['is_enabled'];
    }

    public function getUserEmail($userId) {
        $stmt = $this->pdo->prepare("
            SELECT email FROM users
            WHERE id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['email'] : null;
    }

    public function sendNotification($userId, $eventType, $subject, $message) {
        // Vérifier si l'utilisateur souhaite recevoir cette notification
        if (!$this->shouldSendNotification($userId, $eventType)) {
            return false;
        }
        
        // Récupérer l'email de l'utilisateur
        $toEmail = $this->getUserEmail($userId);
        if (!$toEmail) {
            $this->logEmail($userId, $eventType, 'inconnu@example.com', $subject, $message, 'échec_email_manquant', 'Adresse email manquante pour l\'utilisateur');
            return false;
        }
        
        // Formater le message HTML
        $formattedMessage = $this->formatEmailMessage($message);
        
        // Tenter d'envoyer l'email
        $success = false;
        $errorMessage = '';
        
        try {
            // Configuration pour envoyer directement à MailHog
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: {$this->fromName} <{$this->fromEmail}>\r\n";
            $headers .= "Reply-To: {$this->fromEmail}\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            
            // Utiliser la fonction mail() de PHP avec la configuration MailHog
            $success = mail($toEmail, $subject, $formattedMessage, $headers);
            
            if (!$success) {
                $errorMessage = 'La fonction mail() a échoué. Vérifiez la configuration du serveur SMTP.';
            }
        } catch (Exception $e) {
            $errorMessage = 'Exception lors de l\'envoi: ' . $e->getMessage();
        }
        
        // Enregistrer l'envoi dans les logs
        $status = $success ? 'succès' : 'échec';
        $this->logEmail($userId, $eventType, $toEmail, $subject, $message, $status, $errorMessage);
        
        return $success;
    }
    
    public function sendAlertNotification($userId, $eventType, $details) {
        $eventTitles = [
            'button_pressed' => 'Le bouton "Prêt" a été appuyé',
            'button_released' => 'Le bouton "Prêt" a été relâché',
            'motor_on' => 'Le moteur a été activé',
            'motor_off' => 'Le moteur a été désactivé',
            'led_on' => 'La LED a été allumée',
            'led_off' => 'La LED a été éteinte',
            'potentiometer_changed' => 'La valeur du potentiomètre a changé',
            'system_alert' => 'Alerte système'
        ];
        
        $title = $eventTitles[$eventType] ?? 'Notification ManegePark';
        $subject = 'ManegePark - ' . $title;
        
        $message = '
            <h2>' . htmlspecialchars($title) . '</h2>
            <p>Une action importante s\'est produite sur votre manège :</p>
            <p><strong>' . htmlspecialchars($details) . '</strong></p>
            <p>Date et heure : ' . date('d/m/Y H:i:s') . '</p>
            <p>Veuillez vous connecter au tableau de bord pour plus de détails.</p>
            <p><a href="http://manegepark.com/dashboard" class="btn">Accéder au tableau de bord</a></p>
        ';
        
        return $this->sendNotification($userId, $eventType, $subject, $message);
    }
    
    private function formatEmailMessage($message) {
        // Formater le message en HTML
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>ManegePark Notification</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f9f9f9;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #ffffff;
                    border: 1px solid #e0e0e0;
                    border-radius: 5px;
                }
                .header {
                    background: linear-gradient(135deg, #FF6B00, #0066CC);
                    color: white;
                    padding: 20px;
                    text-align: center;
                    border-radius: 5px 5px 0 0;
                    margin: -20px -20px 20px;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 12px;
                    color: #777;
                }
                .btn {
                    display: inline-block;
                    background-color: #FF6B00;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 15px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>ManegePark</h1>
                </div>
                ' . $message . '
                <div class="footer">
                    <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
                    <p>&copy; ' . date('Y') . ' ManegePark. Tous droits réservés.</p>
                </div>
            </div>
        </body>
        </html>
        ';
        
        return $html;
    }
    
    private function logEmail($userId, $eventType, $toEmail, $subject, $message, $status, $errorMessage = '') {
        $stmt = $this->pdo->prepare("
            INSERT INTO email_logs (user_id, event_type, email_to, subject, message, status, error_message)
            VALUES (:user_id, :event_type, :email_to, :subject, :message, :status, :error_message)
        ");
        
        return $stmt->execute([
            'user_id' => $userId,
            'event_type' => $eventType,
            'email_to' => $toEmail,
            'subject' => $subject,
            'message' => $message,
            'status' => $status,
            'error_message' => $errorMessage
        ]);
    }

    public function getEmailLogs($userId = null, $limit = 50) {
        $sql = "
            SELECT el.*, u.username 
            FROM email_logs el
            JOIN users u ON el.user_id = u.id
        ";
        $params = [];
        
        if ($userId) {
            $sql .= " WHERE el.user_id = :user_id";
            $params['user_id'] = $userId;
        }
        
        $sql .= " ORDER BY el.sent_at DESC LIMIT :limit";
        $params['limit'] = $limit;
        
        $stmt = $this->pdo->prepare($sql);
        
        // Bind les paramètres
        foreach ($params as $key => $value) {
            if ($key === 'limit') {
                $stmt->bindValue(':limit', $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':' . $key, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 