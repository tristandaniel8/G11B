<?php
// Script pour corriger l'encodage de la table sensor_data

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
    
    // Modifier l'encodage de la table
    $pdo->exec("ALTER TABLE sensor_data CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "Conversion de la table sensor_data en UTF-8 réussie.\n";
    
    // Corriger spécifiquement les enregistrements avec ID de 1 à 25
    $stmt = $pdo->query("SELECT id, button_status FROM sensor_data WHERE id BETWEEN 1 AND 25");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Nombre d'enregistrements à corriger: " . count($records) . "\n";
    
    // Corriger l'encodage de chaque enregistrement
    foreach ($records as $record) {
        $id = $record['id'];
        $buttonStatus = $record['button_status'];
        
        // Remplacer directement par les valeurs correctes
        if (strpos($buttonStatus, 'Appuy') !== false) {
            $correctedStatus = 'Appuyé';
            
            // Mettre à jour l'enregistrement
            $updateStmt = $pdo->prepare("UPDATE sensor_data SET button_status = :button_status WHERE id = :id");
            $updateStmt->execute([
                'button_status' => $correctedStatus,
                'id' => $id
            ]);
            
            echo "ID $id: Corrigé en '$correctedStatus'\n";
        } elseif (strpos($buttonStatus, 'Non Appuy') !== false) {
            $correctedStatus = 'Non Appuyé';
            
            // Mettre à jour l'enregistrement
            $updateStmt = $pdo->prepare("UPDATE sensor_data SET button_status = :button_status WHERE id = :id");
            $updateStmt->execute([
                'button_status' => $correctedStatus,
                'id' => $id
            ]);
            
            echo "ID $id: Corrigé en '$correctedStatus'\n";
        } else {
            echo "ID $id: '$buttonStatus' (pas besoin de correction)\n";
        }
    }
    
    echo "Correction des enregistrements terminée.\n";
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 