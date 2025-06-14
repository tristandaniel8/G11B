<?php
// Script pour ajouter la colonne error_message à la table email_logs

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
    
    // Vérifier si la colonne error_message existe déjà
    $stmt = $pdo->query("SHOW COLUMNS FROM email_logs LIKE 'error_message'");
    $columnExists = $stmt->fetch();
    
    if ($columnExists) {
        echo "La colonne 'error_message' existe déjà dans la table 'email_logs'.\n";
    } else {
        // Ajouter la colonne error_message
        $pdo->exec("ALTER TABLE email_logs ADD COLUMN error_message TEXT NULL");
        echo "La colonne 'error_message' a été ajoutée à la table 'email_logs'.\n";
    }
    
    echo "Opération terminée avec succès.\n";
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 