<?php

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/EmailNotification.php';

class EmailNotificationController {
    private EmailNotification $emailNotification;
    private PDO $pdo;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        $db_host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_pass = getenv('DB_PASS');
        
        $db = new Database($db_host, $db_name, $db_user, $db_pass);
        $this->pdo = $db->getConnection();
        $this->emailNotification = new EmailNotification($this->pdo);
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        $isAdmin = ($_SESSION['role'] === 'admin');
        
        $notificationSettings = $this->emailNotification->getUserNotificationSettings($userId);
        $settings = [];
        foreach ($notificationSettings as $setting) {
            $settings[$setting['event_type']] = $setting['is_enabled'];
        }
        
        $emailLogs = $isAdmin ? $this->emailNotification->getEmailLogs() : $this->emailNotification->getEmailLogs($userId);
        
        $successMessage = '';
        $errorMessage = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_settings'])) {
                // Define relevant event types for notifications
                $eventTypes = [
                    'motor_speed_changed', // General speed change
                    'system_alert'         // For critical system issues
                ];
                
                foreach ($eventTypes as $eventType) {
                    $isEnabled = isset($_POST[$eventType]) ? true : false;
                    $this->emailNotification->updateNotificationSetting($userId, $eventType, $isEnabled);
                }
                $successMessage = 'Vos préférences de notification ont été mises à jour avec succès.';
                // Refresh settings
                $notificationSettings = $this->emailNotification->getUserNotificationSettings($userId);
                $settings = [];
                foreach ($notificationSettings as $setting) {
                    $settings[$setting['event_type']] = $setting['is_enabled'];
                }

            } elseif (isset($_POST['send_test'])) {
                $testSubject = 'Test de notification ManegePark';
                $testMessage = '
                    <h2>Ceci est un test de notification</h2>
                    <p>Bonjour ' . htmlspecialchars($username) . ',</p>
                    <p>Ce message confirme que votre système de notification par email fonctionne correctement.</p>
                    <p>Vous pouvez maintenant recevoir des alertes concernant l\'état de votre manège.</p>
                    <p>Cordialement,<br>L\'équipe ManegePark</p>
                ';
                
                if ($this->emailNotification->sendNotification($userId, 'test', $testSubject, $testMessage)) {
                    $successMessage = 'Un email de test a été envoyé avec succès.';
                } else {
                    $errorMessage = 'Échec de l\'envoi de l\'email de test. Veuillez vérifier vos paramètres et les logs du serveur mail.';
                }
                 // Refresh logs after test
                $emailLogs = $isAdmin ? $this->emailNotification->getEmailLogs() : $this->emailNotification->getEmailLogs($userId);
            }
        }
        
        require_once __DIR__ . '/../Views/email_notifications.php';
    }
}