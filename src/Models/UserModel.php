<?php

class UserModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->createTablesIfNotExist();
    }

    private function createTablesIfNotExist() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(255) DEFAULT NULL UNIQUE,
                role ENUM('admin', 'security') NOT NULL DEFAULT 'security',
                reset_token_hash VARCHAR(255) DEFAULT NULL,
                reset_token_expires_at DATETIME DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        if ($stmt->fetchColumn() == 0) {
            $this->createUser('admin', 'admin123', 'admin@example.com', 'admin');
        }
    }

    public function createUser($username, $password, $email, $role = 'security') {
        if (empty($email)) {
            throw new InvalidArgumentException('Email cannot be empty.');
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, password, email, role)
            VALUES (:username, :password, :email, :role)
        ");
        
        try {
            return $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'email' => $email,
                'role' => $role
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Unique constraint violation
                if (strpos($e->getMessage(), 'username') !== false) {
                    throw new PDOException("Username already exists.", 23000);
                } elseif (strpos($e->getMessage(), 'email') !== false) {
                    throw new PDOException("Email already exists.", 23000);
                }
            }
            throw $e; // Re-throw other PDO exceptions
        }
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
            SELECT id, username, email, role, created_at FROM users
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
    
    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE email = :email
        ");
        $stmt->execute(['email' => $email]);
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

    public function storePasswordResetToken($userId, $tokenHash, $expiresAt) {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET reset_token_hash = :token_hash, reset_token_expires_at = :expires_at
            WHERE id = :user_id
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt
        ]);
    }

    public function getUserByResetToken($tokenHash) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users 
            WHERE reset_token_hash = :token_hash AND reset_token_expires_at > NOW()
        ");
        $stmt->execute(['token_hash' => $tokenHash]);
        return $stmt->fetch();
    }

    public function updateUserPassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET password = :password, reset_token_hash = NULL, reset_token_expires_at = NULL
            WHERE id = :user_id
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'password' => $hashedPassword
        ]);
    }
}