<?php
// Script pour réinitialiser les 25 premiers enregistrements avec des valeurs correctement encodées

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Models/Database.php';

// Configuration de la base de données
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');

try {
    // Connexion à la base de données
    $db = new Database($db_host, $db_name, $db_user, $db_pass);
    $pdo = $db->getConnection();
    
    echo "Connexion à la base de données réussie.\n";
    
    // Supprimer les 25 premiers enregistrements
    $pdo->exec("DELETE FROM sensor_data WHERE id BETWEEN 1 AND 25");
    echo "Les 25 premiers enregistrements ont été supprimés.\n";
    
    // Réinitialiser l'auto-increment
    $pdo->exec("ALTER TABLE sensor_data AUTO_INCREMENT = 1");
    echo "Auto-increment réinitialisé à 1.\n";
    
    // Insérer 25 nouveaux enregistrements avec des valeurs correctement encodées
    echo "Insertion de 25 nouveaux enregistrements...\n";
    
    $startTime = strtotime('-24 hours');
    $interval = 3600; // 1 heure
    
    for ($i = 0; $i < 25; $i++) {
        $timestamp = $startTime + ($i * $interval);
        
        // Alterner entre "Appuyé" et "Non Appuyé"
        $buttonStatus = ($i % 2 == 0) ? 'Appuyé' : 'Non Appuyé';
        
        // Si le bouton est appuyé, le moteur et la LED sont activés
        $motorStatus = ($buttonStatus === 'Appuyé') ? 1 : 0;
        $ledStatus = ($buttonStatus === 'Appuyé') ? 1 : 0;
        
        // Valeur du potentiomètre entre 0 et 1023
        $potentiometerValue = rand(0, 1023);
        
        $stmt = $pdo->prepare("
            INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
            VALUES (:timestamp, :button_status, :motor_status, :led_status, :potentiometer_value)
        ");
        
        $stmt->execute([
            'timestamp' => date('Y-m-d H:i:s', $timestamp),
            'button_status' => $buttonStatus,
            'motor_status' => $motorStatus,
            'led_status' => $ledStatus,
            'potentiometer_value' => $potentiometerValue
        ]);
        
        echo "Enregistrement #" . ($i + 1) . " inséré: " . $buttonStatus . "\n";
    }
    
    echo "25 nouveaux enregistrements ont été insérés avec succès.\n";
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 