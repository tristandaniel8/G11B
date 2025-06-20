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
        $this->sensorModel = new SensorModel($this->pdo);
        $this->emailNotification = new EmailNotification($this->pdo);
        
        $city = $_SESSION['weather_city'] ?? WEATHER_DEFAULT_CITY;
        $this->weatherModel = new WeatherModel(WEATHER_API_KEY, $city);
    }
    
    public function index() {
        $currentMotorSpeed = $this->sensorModel->getMotorSpeed(1); 
        $weatherData = $this->weatherModel->getCurrentWeather();
        
        if (!is_array($weatherData)) {
            $weatherData = ['error' => 'Données météo non disponibles'];
        }

        $latestTemperature = $this->sensorModel->getLatestTemperatureReading();
        
        require_once __DIR__ . '/../Views/dashboard.php';
    }
    
    public function history() {
    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $itemsPerPage = 15; // Or make this configurable

    // Get total count for pagination
    $totalItems = $this->sensorModel->getFilteredMotorSpeedHistoryCount(1, $startDate, $endDate);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $offset = ($page - 1) * $itemsPerPage;

    // Fetch paginated history
    $motorSpeedHistory = $this->sensorModel->getFilteredMotorSpeedHistory(1, $startDate, $endDate, $itemsPerPage, $offset);
    
    if (isset($_GET['export']) && $_GET['export'] === 'csv') {
        // Fetch all data for CSV export, not just paginated
        $allMotorSpeedHistory = $this->sensorModel->getFilteredMotorSpeedHistory(1, $startDate, $endDate, $totalItems, 0);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="motor_speed_history.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); 
        
        fputcsv($output, ['ID Mise à Jour', 'Date/Heure', 'Nouvelle Vitesse (0-10)']);
        
        foreach ($allMotorSpeedHistory as $row) {
            fputcsv($output, [
                $row['updateId'],
                $row['updateTime'],
                $row['newSpeed']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    require_once __DIR__ . '/../Views/history.php';
    }
    
    public function updateMotorSpeed() { // Renamed from updateActuators
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $speed = isset($_POST['motor_speed']) ? (int)$_POST['motor_speed'] : -1; // Expect motor_speed
            $motorId = 1; // Assuming motorId 1

            if ($speed < 0 || $speed > 10) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Vitesse invalide.']);
                exit;
            }

            $oldSpeed = $this->sensorModel->getMotorSpeed($motorId);
            $success = $this->sensorModel->updateMotorSpeed($motorId, $speed);
            
            if ($success && $speed != $oldSpeed) {
                $userId = $_SESSION['user_id'];
                $eventType = 'motor_speed_changed';
                $details = "La vitesse du moteur a été changée de {$oldSpeed} à {$speed}.";
                // Consider if notification is needed for every speed change or only significant ones/on-off
                $this->emailNotification->sendAlertNotification($userId, $eventType, $details);
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
        header('Location: /dashboard');
        exit;
    }
    
    public function updateWeatherCity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['city'])) {
            $city = trim($_POST['city']);
            if (!empty($city)) {
                $_SESSION['weather_city'] = $city;
                $this->weatherModel->setCity($city); // Update in model instance
            }
        }
        header('Location: /dashboard');
        exit;
    }
    
    public function getMotorSpeedHistoryJson() {
        // This can be used by an AJAX call from the history page graph
        $motorId = $_GET['motorId'] ?? 1;
        $limit = $_GET['limit'] ?? 50;
        $motorSpeedHistory = $this->sensorModel->getMotorSpeedHistory((int)$motorId, (int)$limit);
        
        $chartData = [];
        foreach ($motorSpeedHistory as $entry) {
            $chartData[] = [
                'time' => $entry['updateTime'],
                'speed' => $entry['newSpeed']
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($chartData);
        exit;
    }
}