<?php

require_once __DIR__ . '/Models/Database.php';
require_once __DIR__ . '/Models/SensorModel.php';
require_once __DIR__ . '/Models/UserModel.php';

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
    
    // Initialisation des modèles
    $userModel = new UserModel($pdo);
    $sensorModel = new SensorModel($pdo);
    
    echo "Modèles initialisés.\n";
    
    // Vérifier si l'utilisateur security existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'security'");
    $stmt->execute();
    $securityExists = (bool)$stmt->fetchColumn();
    
    if (!$securityExists) {
        $userModel->createUser('security', 'security123', 'security');
        echo "Utilisateur 'security' créé.\n";
    } else {
        echo "L'utilisateur 'security' existe déjà.\n";
    }
    
    // Vider la table sensor_data pour éviter les doublons
    $pdo->exec("TRUNCATE TABLE sensor_data");
    echo "Table sensor_data vidée.\n";
    
    // Génération de données de capteurs aléatoires pour les 24 dernières heures
    $startTime = strtotime('-24 hours');
    $endTime = time();
    $interval = 600; // 10 minutes
    
    echo "Génération de données de capteurs pour les dernières 24 heures...\n";
    
    for ($timestamp = $startTime; $timestamp <= $endTime; $timestamp += $interval) {
        // Simuler un bouton appuyé environ 30% du temps
        $buttonStatus = (rand(1, 10) <= 3) ? 'Appuyé' : 'Non Appuyé';
        
        // Si le bouton est appuyé, le moteur et la LED sont activés
        $motorStatus = ($buttonStatus === 'Appuyé') ? 1 : (rand(0, 1));
        $ledStatus = ($buttonStatus === 'Appuyé') ? 1 : (rand(0, 1));
        
        // Valeur du potentiomètre entre 0 et 1023
        $potentiometerValue = rand(0, 1023);
        
        // Insérer les données avec un timestamp spécifique
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
    }
    
    echo "Données de test générées avec succès.\n";
    echo "Nombre total d'enregistrements: " . floor(($endTime - $startTime) / $interval) . "\n";
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 