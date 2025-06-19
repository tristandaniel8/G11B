<?php
/**
 * Script d'initialisation pour les tables d'historique du moteur
 * Ce script crée les tables et initialise les données de base
 */

// Inclure les fichiers nécessaires
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Models/Database.php';

echo "Initialisation des tables d'historique du moteur...\n";

try {
    // Connexion à la base de données
    $db_host = getenv('DB_HOST');
    $db_name = getenv('DB_NAME');
    $db_user = getenv('DB_USER');
    $db_pass = getenv('DB_PASS');
    
    $db = new Database($db_host, $db_name, $db_user, $db_pass);
    $pdo = $db->getConnection();
    
    echo "Connexion à la base de données réussie.\n";
    
    // Créer la table motor_data
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS motor_data (
            motorId INT AUTO_INCREMENT PRIMARY KEY,
            motorSpeed INT NOT NULL DEFAULT 0 COMMENT 'Vitesse du moteur de 0 à 10'
        )
    ");
    echo "Table motor_data créée ou déjà existante.\n";
    
    // Créer la table motor_updates
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS motor_updates (
            updateId INT AUTO_INCREMENT PRIMARY KEY,
            motorId INT NOT NULL,
            updateTime DATETIME DEFAULT CURRENT_TIMESTAMP,
            newSpeed INT NOT NULL COMMENT 'Nouvelle vitesse du moteur de 0 à 10',
            FOREIGN KEY (motorId) REFERENCES motor_data(motorId)
        )
    ");
    echo "Table motor_updates créée ou déjà existante.\n";
    
    // Vérifier si la table motor_data est vide
    $stmt = $pdo->query("SELECT COUNT(*) FROM motor_data");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Insérer un moteur par défaut
        $pdo->exec("INSERT INTO motor_data (motorId, motorSpeed) VALUES (1, 0)");
        echo "Moteur par défaut ajouté à la table motor_data.\n";
        
        // Récupérer les données existantes du moteur depuis sensor_data
        $stmt = $pdo->query("
            SELECT timestamp, potentiometer_value 
            FROM sensor_data 
            ORDER BY timestamp
        ");
        
        $motorData = $stmt->fetchAll();
        
        if (count($motorData) > 0) {
            // Préparer l'insertion dans motor_updates
            $insertStmt = $pdo->prepare("
                INSERT INTO motor_updates (motorId, updateTime, newSpeed)
                VALUES (1, :updateTime, :newSpeed)
            ");
            
            $insertCount = 0;
            foreach ($motorData as $row) {
                // Convertir la valeur du potentiomètre en vitesse (0-10)
                $speed = round(($row['potentiometer_value'] / 1023) * 10);
                
                $insertStmt->execute([
                    'updateTime' => $row['timestamp'],
                    'newSpeed' => $speed
                ]);
                
                $insertCount++;
            }
            
            echo "$insertCount entrées d'historique de moteur ajoutées à partir des données existantes.\n";
        } else {
            echo "Aucune donnée existante à migrer vers motor_updates.\n";
        }
    } else {
        echo "La table motor_data contient déjà des données.\n";
    }
    
    echo "Initialisation des tables d'historique du moteur terminée avec succès.\n";
    
} catch (PDOException $e) {
    echo "Erreur lors de l'initialisation des tables: " . $e->getMessage() . "\n";
} 