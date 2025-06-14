<?php

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/EmailNotification.php';

class EmailNotificationController {
    private EmailNotification $emailNotification;
    
    public function __construct() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        $db_host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_pass = getenv('DB_PASS');
        
        $db = new Database($db_host, $db_name, $db_user, $db_pass);
        $this->emailNotification = new EmailNotification($db->getConnection());
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        $isAdmin = $_SESSION['role'] === 'admin';
        
        // Récupérer les paramètres de notification de l'utilisateur
        $notificationSettings = $this->emailNotification->getUserNotificationSettings($userId);
        
        // Préparer un tableau associatif pour faciliter l'accès aux paramètres
        $settings = [];
        foreach ($notificationSettings as $setting) {
            $settings[$setting['event_type']] = $setting['is_enabled'];
        }
        
        // Récupérer l'historique des emails
        if ($isAdmin) {
            // Les administrateurs peuvent voir tous les emails
            $emailLogs = $this->emailNotification->getEmailLogs();
        } else {
            // Les utilisateurs normaux ne voient que leurs emails
            $emailLogs = $this->emailNotification->getEmailLogs($userId);
        }
        
        // Traiter la mise à jour des paramètres si le formulaire est soumis
        $success = '';
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
            // Événements disponibles
            $eventTypes = [
                'button_pressed', 
                'button_released',
                'motor_on', 
                'motor_off',
                'led_on', 
                'led_off',
                'potentiometer_changed',
                'system_alert'
            ];
            
            foreach ($eventTypes as $eventType) {
                $isEnabled = isset($_POST[$eventType]) ? true : false;
                $this->emailNotification->updateNotificationSetting($userId, $eventType, $isEnabled);
            }
            
            $success = 'Vos préférences de notification ont été mises à jour avec succès.';
            
            // Rafraîchir les paramètres après la mise à jour
            $notificationSettings = $this->emailNotification->getUserNotificationSettings($userId);
            $settings = [];
            foreach ($notificationSettings as $setting) {
                $settings[$setting['event_type']] = $setting['is_enabled'];
            }
        }
        
        // Tester l'envoi d'un email si demandé
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_test'])) {
            $testSubject = 'Test de notification ManegePark';
            $testMessage = '
                <h2>Ceci est un test de notification</h2>
                <p>Bonjour ' . htmlspecialchars($username) . ',</p>
                <p>Ce message confirme que votre système de notification par email fonctionne correctement.</p>
                <p>Vous pouvez maintenant recevoir des alertes concernant l\'état de votre manège.</p>
                <p>Cordialement,<br>L\'équipe ManegePark</p>
            ';
            
            $success = $this->emailNotification->sendNotification($userId, 'test', $testSubject, $testMessage)
                ? 'Un email de test a été envoyé avec succès.'
                : 'Échec de l\'envoi de l\'email de test. Veuillez vérifier vos paramètres.';
        }
        
        // Afficher la vue
        require_once __DIR__ . '/../Views/email_notifications.php';
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
        
        return $this->emailNotification->sendNotification($userId, $eventType, $subject, $message);
    }
} 