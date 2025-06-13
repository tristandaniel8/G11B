<?php

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AdminController {
    private UserModel $userModel;
    
    public function __construct() {
        // Vérifier si l'utilisateur est connecté et est un administrateur
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
        
        $db_host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_pass = getenv('DB_PASS');
        
        $db = new Database($db_host, $db_name, $db_user, $db_pass);
        $this->userModel = new UserModel($db->getConnection());
    }
    
    public function index() {
        // Récupérer tous les utilisateurs
        $users = $this->userModel->getAllUsers();
        
        require_once __DIR__ . '/../Views/admin.php';
    }
    
    public function createUser() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'security';
            
            if (empty($username) || empty($password)) {
                $error = "Tous les champs sont obligatoires";
            } else {
                try {
                    $this->userModel->createUser($username, $password, $role);
                    $success = "Utilisateur créé avec succès";
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) { // Code d'erreur pour violation de contrainte unique
                        $error = "Ce nom d'utilisateur est déjà utilisé";
                    } else {
                        $error = "Une erreur est survenue lors de la création de l'utilisateur";
                    }
                }
            }
        }
        
        // Récupérer tous les utilisateurs
        $users = $this->userModel->getAllUsers();
        
        require_once __DIR__ . '/../Views/admin.php';
    }
    
    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
            $userId = (int)$_POST['user_id'];
            
            $this->userModel->deleteUser($userId);
        }
        
        // Rediriger vers la page d'administration
        header('Location: /admin');
        exit;
    }
    
    public function updateUserRole() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['role'])) {
            $userId = (int)$_POST['user_id'];
            $role = $_POST['role'];
            
            $this->userModel->updateUserRole($userId, $role);
            
            // Retourner une réponse JSON pour les requêtes AJAX
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
        }
        
        // Rediriger vers la page d'administration
        header('Location: /admin');
        exit;
    }
} 