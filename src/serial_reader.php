<?php

require_once __DIR__ . '/Models/Database.php';
require_once __DIR__ . '/Models/SensorModel.php';

// Configuration de la base de données
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');

// Configuration du port série (à adapter selon votre système)
$serialPort = '/dev/ttyACM0';  // Linux
// $serialPort = 'COM3';       // Windows

// Fonction pour lire les données du port série
function readSerialData($port) {
    if (!file_exists($port)) {
        return false;
    }
    
    $handle = fopen($port, "r");
    if ($handle) {
        $data = fgets($handle);
        fclose($handle);
        return $data;
    }
    
    return false;
}

// Fonction pour parser les données reçues
function parseSerialData($data) {
    // Format attendu: "button:Appuyé;motor:1;led:0;potentiometer:512"
    $result = [
        'button_status' => 'Non Appuyé',
        'motor_status' => 0,
        'led_status' => 0,
        'potentiometer_value' => 0
    ];
    
    $parts = explode(';', $data);
    foreach ($parts as $part) {
        $keyValue = explode(':', $part);
        if (count($keyValue) == 2) {
            $key = trim($keyValue[0]);
            $value = trim($keyValue[1]);
            
            switch ($key) {
                case 'button':
                    $result['button_status'] = $value;
                    break;
                case 'motor':
                    $result['motor_status'] = (int)$value;
                    break;
                case 'led':
                    $result['led_status'] = (int)$value;
                    break;
                case 'potentiometer':
                    $result['potentiometer_value'] = (int)$value;
                    break;
            }
        }
    }
    
    return $result;
}

// Boucle principale
try {
    // Connexion à la base de données
    $db = new Database($db_host, $db_name, $db_user, $db_pass);
    $sensorModel = new SensorModel($db->getConnection());
    
    // Simulation pour le développement (à remplacer par la lecture réelle)
    $useSimulation = true;
    
    if ($useSimulation) {
        // Données simulées pour le développement
        $buttonStatus = (rand(0, 1) == 1) ? 'Appuyé' : 'Non Appuyé';
        $motorStatus = rand(0, 1);
        $ledStatus = rand(0, 1);
        $potentiometerValue = rand(0, 1023);
        
        // Si le bouton est appuyé, activer le moteur et la LED
        if ($buttonStatus == 'Appuyé') {
            $motorStatus = 1;
            $ledStatus = 1;
        }
        
        // Enregistrer les données dans la base de données
        $sensorModel->saveData($buttonStatus, $motorStatus, $ledStatus, $potentiometerValue);
        
        echo "Données simulées enregistrées : \n";
        echo "Button: $buttonStatus\n";
        echo "Motor: $motorStatus\n";
        echo "LED: $ledStatus\n";
        echo "Potentiometer: $potentiometerValue\n";
    } else {
        // Lecture réelle du port série
        $serialData = readSerialData($serialPort);
        
        if ($serialData !== false) {
            $parsedData = parseSerialData($serialData);
            
            // Enregistrer les données dans la base de données
            $sensorModel->saveData(
                $parsedData['button_status'],
                $parsedData['motor_status'],
                $parsedData['led_status'],
                $parsedData['potentiometer_value']
            );
            
            echo "Données enregistrées avec succès!\n";
        } else {
            echo "Erreur: Impossible de lire les données du port série.\n";
        }
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 