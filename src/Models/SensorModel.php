<?php

class SensorModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getMotorSpeed(int $motorId = 1): int {
        $stmt = $this->pdo->prepare("SELECT motorSpeed FROM motors WHERE motorId = :motorId");
        $stmt->execute(['motorId' => $motorId]);
        $result = $stmt->fetch();
        return $result ? (int)$result['motorSpeed'] : 0;
    }

    public function updateMotorSpeed(int $motorId, int $speed): bool {
        if ($speed < 0 || $speed > 10) {
            throw new InvalidArgumentException("Speed must be between 0 and 10.");
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO motors (motorId, motorSpeed) VALUES (:motorId, :speed)
                 ON DUPLICATE KEY UPDATE motorSpeed = :speed"
            );
            $stmt->execute(['motorId' => $motorId, 'speed' => $speed]);

            $stmtLog = $this->pdo->prepare(
                "INSERT INTO speed_updates (motorId, newSpeed) VALUES (:motorId, :newSpeed)"
            );
            $stmtLog->execute(['motorId' => $motorId, 'newSpeed' => $speed]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error updating motor speed for motorId {$motorId}: " . $e->getMessage());
            return false;
        }
    }

    public function getMotorSpeedHistory(int $motorId = 1, int $limit = 50): array {
        $stmt = $this->pdo->prepare(
            "SELECT updateId, updateTime, newSpeed 
             FROM speed_updates 
             WHERE motorId = :motorId
             ORDER BY updateTime DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':motorId', $motorId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_reverse($stmt->fetchAll()); 
    }
    
    public function getFilteredMotorSpeedHistoryCount(int $motorId = 1, ?string $startDate = null, ?string $endDate = null): int {
        $query = "SELECT COUNT(*) FROM speed_updates WHERE motorId = :motorId";
        $params = ['motorId' => $motorId];

        if ($startDate) {
            $query .= " AND DATE(updateTime) >= :start_date";
            $params['start_date'] = $startDate;
        }
        if ($endDate) {
            $query .= " AND DATE(updateTime) <= :end_date";
            $params['end_date'] = $endDate;
        }
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getFilteredMotorSpeedHistory(int $motorId = 1, ?string $startDate = null, ?string $endDate = null, int $limit = 200, int $offset = 0): array {
        $query = "SELECT updateId, updateTime, newSpeed FROM speed_updates WHERE motorId = :motorId";
        $queryParams = ['motorId' => $motorId];

        if ($startDate) {
            $query .= " AND DATE(updateTime) >= :start_date_val"; // Use different placeholder name
            $queryParams['start_date_val'] = $startDate;
        }
        if ($endDate) {
            $query .= " AND DATE(updateTime) <= :end_date_val";   // Use different placeholder name
            $queryParams['end_date_val'] = $endDate;
        }
        $query .= " ORDER BY updateTime DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($query);
        
        foreach($queryParams as $key => &$val) { // Pass by reference for bindValue
            $stmt->bindValue(":$key", $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        // For chart, we usually want chronological. For table, reverse chronological is fine.
        // The controller will reverse for the chart if needed.
        return $stmt->fetchAll(); 
    }

    public function getLatestTemperatureReading(): ?array {
        // Assuming 'capteur_temp_simule' is the correct table name
        // and you want the absolute latest reading from any sensor id in that table.
        // If you have multiple temperature sensors and need a specific one, you'd add a WHERE clause.
        $stmt = $this->pdo->query(
            "SELECT id, dates, valeur 
             FROM capteur_temp_simule 
             ORDER BY dates DESC 
             LIMIT 1"
        );
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null; // Return null if no data
    }
}