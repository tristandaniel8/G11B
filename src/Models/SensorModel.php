<?php

class SensorModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->createTablesIfNotExist();
    }

    private function createTablesIfNotExist() {
        // Création de la table pour stocker les données des capteurs
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS sensor_data (
                id INT AUTO_INCREMENT PRIMARY KEY,
                timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
                button_status VARCHAR(20) NOT NULL,
                motor_status BOOLEAN NOT NULL,
                led_status BOOLEAN NOT NULL,
                potentiometer_value INT NOT NULL
            )
        ");
    }

    public function saveData($buttonStatus, $motorStatus, $ledStatus, $potentiometerValue) {
        $stmt = $this->pdo->prepare("
            INSERT INTO sensor_data (button_status, motor_status, led_status, potentiometer_value)
            VALUES (:button_status, :motor_status, :led_status, :potentiometer_value)
        ");
        
        return $stmt->execute([
            'button_status' => $buttonStatus,
            'motor_status' => $motorStatus,
            'led_status' => $ledStatus,
            'potentiometer_value' => $potentiometerValue
        ]);
    }

    public function getLatestData() {
        $stmt = $this->pdo->query("
            SELECT * FROM sensor_data
            ORDER BY timestamp DESC
            LIMIT 1
        ");
        
        $result = $stmt->fetch();
        
        // Si aucune donnée n'est trouvée, retourner un tableau par défaut au lieu de false
        if ($result === false) {
            return [
                'button_status' => 'Non Appuyé',
                'motor_status' => 0,
                'led_status' => 0,
                'potentiometer_value' => 0
            ];
        }
        
        return $result;
    }

    public function getHistory($limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM sensor_data
            ORDER BY timestamp DESC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getFilteredHistory($startDate = null, $endDate = null, $type = null) {
        $query = "SELECT * FROM sensor_data WHERE 1=1";
        $params = [];
        
        if ($startDate) {
            $query .= " AND timestamp >= :start_date";
            $params['start_date'] = $startDate;
        }
        
        if ($endDate) {
            $query .= " AND timestamp <= :end_date";
            $params['end_date'] = $endDate;
        }
        
        if ($type) {
            switch ($type) {
                case 'button':
                    $query .= " AND button_status = 'Appuyé'";
                    break;
                case 'motor':
                    $query .= " AND motor_status = 1";
                    break;
                case 'led':
                    $query .= " AND led_status = 1";
                    break;
            }
        }
        
        $query .= " ORDER BY timestamp DESC";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    public function updateActuators($motorStatus, $ledStatus, $potentiometerValue) {
        $stmt = $this->pdo->prepare("
            INSERT INTO sensor_data (button_status, motor_status, led_status, potentiometer_value)
            SELECT 'Non Appuyé', :motor_status, :led_status, :potentiometer_value
            FROM dual
        ");
        
        return $stmt->execute([
            'motor_status' => $motorStatus,
            'led_status' => $ledStatus,
            'potentiometer_value' => $potentiometerValue
        ]);
    }
} 