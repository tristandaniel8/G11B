<?php
// Script pour ajouter la colonne email à la table users

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
    
    // Vérifier si la colonne email existe déjà
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email'");
    $columnExists = $stmt->fetch();
    
    if ($columnExists) {
        echo "La colonne 'email' existe déjà dans la table 'users'.\n";
    } else {
        // Ajouter la colonne email
        $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(255) DEFAULT NULL");
        echo "La colonne 'email' a été ajoutée à la table 'users'.\n";
        
        // Mettre à jour les utilisateurs existants avec des emails fictifs
        $stmt = $pdo->query("SELECT id, username FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $user) {
            $email = strtolower($user['username']) . "@example.com";
            $updateStmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");
            $updateStmt->execute([
                'email' => $email,
                'id' => $user['id']
            ]);
            echo "Email fictif '{$email}' ajouté pour l'utilisateur '{$user['username']}'.\n";
        }
    }
    
    echo "Opération terminée avec succès.\n";
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 