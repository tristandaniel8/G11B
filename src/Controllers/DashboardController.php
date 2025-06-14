<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/SensorModel.php';
require_once __DIR__ . '/../Models/EmailNotification.php';
require_once __DIR__ . '/../Models/WeatherModel.php';

class DashboardController {
    private SensorModel $sensorModel;
    private EmailNotification $emailNotification;
    private WeatherModel $weatherModel;
    
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
        $this->sensorModel = new SensorModel($db->getConnection());
        $this->emailNotification = new EmailNotification($db->getConnection());
        
        // Utiliser la ville stockée dans la session ou la ville par défaut
        $city = $_SESSION['weather_city'] ?? WEATHER_DEFAULT_CITY;
        
        // Initialiser le modèle météo avec la clé API par défaut
        $this->weatherModel = new WeatherModel(WEATHER_API_KEY, $city);
    }
    
    public function index() {
        // Récupérer les dernières données des capteurs
        $latestData = $this->sensorModel->getLatestData();
        
        // Récupérer l'historique récent
        $history = $this->sensorModel->getHistory(5);
        
        // Récupérer les données météo
        $weatherData = $this->weatherModel->getCurrentWeather();
        
        require_once __DIR__ . '/../Views/dashboard.php';
    }
    
    public function history() {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $type = $_GET['type'] ?? null;
        
        // Récupérer l'historique filtré
        $history = $this->sensorModel->getFilteredHistory($startDate, $endDate, $type);
        
        // Exporter en CSV si demandé
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="sensor_history.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Timestamp', 'Button Status', 'Motor Status', 'LED Status', 'Potentiometer Value']);
            
            foreach ($history as $row) {
                fputcsv($output, [
                    $row['id'],
                    $row['timestamp'],
                    $row['button_status'],
                    $row['motor_status'] ? 'On' : 'Off',
                    $row['led_status'] ? 'On' : 'Off',
                    $row['potentiometer_value']
                ]);
            }
            
            fclose($output);
            exit;
        }
        
        require_once __DIR__ . '/../Views/history.php';
    }
    
    public function updateActuators() {
        // Vérifier si c'est une requête AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motorStatus = isset($_POST['motor_status']) ? (int)$_POST['motor_status'] : 0;
            $ledStatus = isset($_POST['led_status']) ? (int)$_POST['led_status'] : 0;
            $potentiometerValue = isset($_POST['potentiometer_value']) ? (int)$_POST['potentiometer_value'] : 0;
            
            // Récupérer les anciennes valeurs pour comparer
            $latestData = $this->sensorModel->getLatestData();
            $oldMotorStatus = $latestData['motor_status'] ?? 0;
            $oldLedStatus = $latestData['led_status'] ?? 0;
            $oldPotentiometerValue = $latestData['potentiometer_value'] ?? 0;
            
            // Mettre à jour les actionneurs dans la base de données
            $success = $this->sensorModel->updateActuators($motorStatus, $ledStatus, $potentiometerValue);
            
            // Envoyer des notifications par email si nécessaire
            $userId = $_SESSION['user_id'];
            
            // Notification pour le moteur
            if ($motorStatus != $oldMotorStatus) {
                $eventType = $motorStatus ? 'motor_on' : 'motor_off';
                $details = $motorStatus ? 'Le moteur a été activé' : 'Le moteur a été désactivé';
                $this->emailNotification->sendAlertNotification($userId, $eventType, $details);
            }
            
            // Notification pour la LED
            if ($ledStatus != $oldLedStatus) {
                $eventType = $ledStatus ? 'led_on' : 'led_off';
                $details = $ledStatus ? 'La LED a été allumée' : 'La LED a été éteinte';
                $this->emailNotification->sendAlertNotification($userId, $eventType, $details);
            }
            
            // Notification pour le potentiomètre (seulement si la valeur a changé significativement)
            if (abs($potentiometerValue - $oldPotentiometerValue) > 50) {
                $eventType = 'potentiometer_changed';
                $details = "La valeur du potentiomètre a changé de $oldPotentiometerValue à $potentiometerValue";
                $this->emailNotification->sendAlertNotification($userId, $eventType, $details);
            }
            
            // Retourner une réponse JSON
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
        
        // Rediriger vers le tableau de bord si ce n'est pas une requête AJAX
        header('Location: /dashboard');
        exit;
    }
    
    /**
     * Met à jour la ville pour les données météo
     */
    public function updateWeatherCity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['city'])) {
            // Récupérer la ville depuis le formulaire
            $city = trim($_POST['city']);
            
            if (!empty($city)) {
                // Stocker la ville dans la session
                $_SESSION['weather_city'] = $city;
                
                // Mettre à jour la ville dans le modèle météo
                $this->weatherModel->setCity($city);
            }
        }
        
        // Rediriger vers le tableau de bord
        header('Location: /dashboard');
        exit;
    }
} 