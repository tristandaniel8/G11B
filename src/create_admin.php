<?php

require_once __DIR__ . '/Models/Database.php';
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
    
    // Création du modèle utilisateur
    $userModel = new UserModel($pdo);
    
    // Suppression de l'utilisateur admin s'il existe déjà
    $pdo->exec("DELETE FROM users WHERE username = 'admin'");
    
    // Création de l'utilisateur admin
    $userModel->createUser('admin', 'admin123', 'admin');
    
    echo "Utilisateur admin créé avec succès.\n";
    
    // Affichage de tous les utilisateurs
    $stmt = $pdo->query("SELECT id, username, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Liste des utilisateurs :\n";
    foreach ($users as $user) {
        echo "ID: {$user['id']}, Username: {$user['username']}, Role: {$user['role']}\n";
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 