<?php

class UserModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->createTablesIfNotExist();
    }

    private function createTablesIfNotExist() {
        // Création de la table utilisateurs
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(255) DEFAULT NULL,
                role ENUM('admin', 'security') NOT NULL DEFAULT 'security',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Vérifier si un admin existe déjà, sinon en créer un par défaut
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        if ($stmt->fetchColumn() == 0) {
            $this->createUser('admin', 'admin123', 'admin');
        }
    }

    public function createUser($username, $password, $role = 'security', $email = null) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, password, role, email)
            VALUES (:username, :password, :role, :email)
        ");
        
        return $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'role' => $role,
            'email' => $email
        ]);
    }

    public function verifyUser($username, $password) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE username = :username
        ");
        
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("
            SELECT id, username, role, created_at FROM users
            ORDER BY created_at DESC
        ");
        
        return $stmt->fetchAll();
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("
            DELETE FROM users WHERE id = :id AND role != 'admin'
        ");
        
        return $stmt->execute(['id' => $id]);
    }

    public function updateUserRole($id, $role) {
        $stmt = $this->pdo->prepare("
            UPDATE users SET role = :role WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'role' => $role
        ]);
    }
    
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE id = :id
        ");
        
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function updateUserEmail($id, $email) {
        $stmt = $this->pdo->prepare("
            UPDATE users SET email = :email WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'email' => $email
        ]);
    }
} 