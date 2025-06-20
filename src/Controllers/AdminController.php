<?php

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AdminController {
    private UserModel $userModel;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard'); // or /login if not logged in at all
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
        $users = $this->userModel->getAllUsers();
        $error = $_SESSION['admin_error'] ?? '';
        $success = $_SESSION['admin_success'] ?? '';
        unset($_SESSION['admin_error'], $_SESSION['admin_success']);
        
        require_once __DIR__ . '/../Views/admin.php';
    }
    
    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'security'; // Default role
            
            if (empty($username) || empty($password) || empty($email)) {
                $_SESSION['admin_error'] = "Tous les champs (nom d'utilisateur, email, mot de passe) sont obligatoires.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $_SESSION['admin_error'] = "Format d'email invalide.";
            } elseif (strlen($password) < 6) {
                 $_SESSION['admin_error'] = "Le mot de passe doit faire au moins 6 caractères.";
            } else {
                try {
                    $this->userModel->createUser($username, $password, $email, $role);
                    $_SESSION['admin_success'] = "Utilisateur '{$username}' créé avec succès.";
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                         if (strpos($e->getMessage(), 'username') !== false) {
                           $_SESSION['admin_error'] = "Ce nom d'utilisateur est déjà utilisé.";
                        } elseif (strpos($e->getMessage(), 'email') !== false) {
                           $_SESSION['admin_error'] = "Cette adresse email est déjà utilisée.";
                        } else {
                           $_SESSION['admin_error'] = "Erreur: Nom d'utilisateur ou email déjà existant.";
                        }
                    } else {
                        $_SESSION['admin_error'] = "Une erreur est survenue: " . $e->getMessage();
                    }
                } catch (InvalidArgumentException $e) {
                     $_SESSION['admin_error'] = $e->getMessage();
                }
            }
        }
        header('Location: /admin');
        exit;
    }
    
    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
            $userId = (int)$_POST['user_id'];
            $userToDelete = $this->userModel->getUserById($userId);

            if ($userToDelete && $userToDelete['id'] == $_SESSION['user_id']) {
                $_SESSION['admin_error'] = "Vous не pouvez pas supprimer votre propre compte.";
            } elseif ($userToDelete && $userToDelete['role'] === 'admin' && $userToDelete['username'] === 'admin') {
                 $_SESSION['admin_error'] = "L'utilisateur admin principal ne peut pas être supprimé.";
            }
            elseif ($this->userModel->deleteUser($userId)) {
                $_SESSION['admin_success'] = "Utilisateur supprimé avec succès.";
            } else {
                $_SESSION['admin_error'] = "Impossible de supprimer l'utilisateur ou l'utilisateur est l'admin principal.";
            }
        }
        header('Location: /admin');
        exit;
    }
    
    public function updateUserRole() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['role'])) {
            $userId = (int)$_POST['user_id'];
            $newRole = $_POST['role'];
            $userToUpdate = $this->userModel->getUserById($userId);

            if ($userToUpdate && $userToUpdate['id'] == $_SESSION['user_id']) {
                 $_SESSION['admin_error'] = "Vous ne pouvez pas changer votre propre rôle.";
            } elseif ($userToUpdate && $userToUpdate['username'] === 'admin' && $newRole !== 'admin') {
                 $_SESSION['admin_error'] = "Le rôle de l'utilisateur admin principal ne peut pas être changé.";
            } elseif ($this->userModel->updateUserRole($userId, $newRole)) {
                $_SESSION['admin_success'] = "Rôle de l'utilisateur mis à jour.";
                 if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => "Rôle mis à jour."]);
                    exit;
                }
            } else {
                 $_SESSION['admin_error'] = "Erreur lors de la mise à jour du rôle.";
            }
        }
         if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            header('Location: /admin');
            exit;
        }
    }
}