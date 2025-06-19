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
        
        // Création des tables pour l'historique du moteur
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS motor_data (
                motorId INT AUTO_INCREMENT PRIMARY KEY,
                motorSpeed INT NOT NULL DEFAULT 0 COMMENT 'Vitesse du moteur de 0 à 10'
            )
        ");
        
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS motor_updates (
                updateId INT AUTO_INCREMENT PRIMARY KEY,
                motorId INT NOT NULL,
                updateTime DATETIME DEFAULT CURRENT_TIMESTAMP,
                newSpeed INT NOT NULL COMMENT 'Nouvelle vitesse du moteur de 0 à 10',
                FOREIGN KEY (motorId) REFERENCES motor_data(motorId)
            )
        ");
        
        // Insérer un moteur par défaut si la table est vide
        $this->pdo->exec("
            INSERT INTO motor_data (motorId, motorSpeed)
            SELECT 1, 0 FROM dual
            WHERE NOT EXISTS (SELECT 1 FROM motor_data WHERE motorId = 1)
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
        // Transaction pour s'assurer que toutes les opérations sont effectuées ou aucune
        $this->pdo->beginTransaction();
        
        try {
            // Mettre à jour les données des capteurs
            $stmt = $this->pdo->prepare("
                INSERT INTO sensor_data (button_status, motor_status, led_status, potentiometer_value)
                SELECT 'Non Appuyé', :motor_status, :led_status, :potentiometer_value
                FROM dual
            ");
            
            $stmt->execute([
                'motor_status' => $motorStatus,
                'led_status' => $ledStatus,
                'potentiometer_value' => $potentiometerValue
            ]);
            
            // Calculer la vitesse du moteur sur une échelle de 0 à 10
            $motorSpeed = round(($potentiometerValue / 1023) * 10);
            
            // Mettre à jour la vitesse dans motor_data
            $stmt = $this->pdo->prepare("
                UPDATE motor_data
                SET motorSpeed = :speed
                WHERE motorId = 1
            ");
            
            $stmt->execute(['speed' => $motorSpeed]);
            
            // Enregistrer la mise à jour dans motor_updates
            $stmt = $this->pdo->prepare("
                INSERT INTO motor_updates (motorId, newSpeed)
                VALUES (1, :speed)
            ");
            
            $stmt->execute(['speed' => $motorSpeed]);
            
            // Valider la transaction
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->pdo->rollBack();
            error_log("Erreur lors de la mise à jour des actionneurs: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère la dernière vitesse du moteur
     */
    public function getLatestMotorSpeed() {
        $stmt = $this->pdo->query("
            SELECT motorSpeed FROM motor_data
            WHERE motorId = 1
        ");
        
        $result = $stmt->fetch();
        return $result ? $result['motorSpeed'] : 0;
    }
    
    /**
     * Récupère l'historique des mises à jour de vitesse du moteur
     */
    public function getMotorSpeedHistory($limit = 20) {
        $stmt = $this->pdo->prepare("
            SELECT updateTime, newSpeed 
            FROM motor_updates 
            WHERE motorId = 1
            ORDER BY updateTime DESC
            LIMIT :limit
        ");
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        
        // Inverser l'ordre pour avoir les données dans l'ordre chronologique
        return array_reverse($results);
    }
} 