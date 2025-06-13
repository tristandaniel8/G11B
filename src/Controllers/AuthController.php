<?php

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AuthController {
    private UserModel $userModel;
    
    public function __construct() {
        $db_host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_pass = getenv('DB_PASS');
        
        $db = new Database($db_host, $db_name, $db_user, $db_pass);
        $this->userModel = new UserModel($db->getConnection());
    }
    
    public function login() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $error = "Tous les champs sont obligatoires";
            } else {
                $user = $this->userModel->verifyUser($username, $password);
                
                if ($user) {
                    // Stocker les informations utilisateur dans la session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    // Rediriger vers le tableau de bord
                    header('Location: /dashboard');
                    exit;
                } else {
                    $error = "Nom d'utilisateur ou mot de passe incorrect";
                }
            }
        }
        
        require_once __DIR__ . '/../Views/login.php';
    }
    
    public function register() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($username) || empty($password) || empty($confirmPassword)) {
                $error = "Tous les champs sont obligatoires";
            } elseif ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas";
            } else {
                try {
                    $this->userModel->createUser($username, $password);
                    
                    // Rediriger vers la page de connexion
                    header('Location: /login?registered=1');
                    exit;
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) { // Code d'erreur pour violation de contrainte unique
                        $error = "Ce nom d'utilisateur est déjà utilisé";
                    } else {
                        $error = "Une erreur est survenue lors de l'inscription";
                    }
                }
            }
        }
        
        require_once __DIR__ . '/../Views/register.php';
    }
    
    public function logout() {
        // Détruire la session
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        header('Location: /');
        exit;
    }
} 