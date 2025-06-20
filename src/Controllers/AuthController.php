<?php

require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/UserModel.php';
require_once __DIR__ . '/../Models/EmailNotification.php'; // For password reset

class AuthController {
    private UserModel $userModel;
    private EmailNotification $emailNotification;
    private PDO $pdo;

    public function __construct() {
        $db_host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_pass = getenv('DB_PASS');
        
        $db = new Database($db_host, $db_name, $db_user, $db_pass);
        $this->pdo = $db->getConnection();
        $this->userModel = new UserModel($this->pdo);
        $this->emailNotification = new EmailNotification($this->pdo);
    }
    
    public function login() {
        $error = '';
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $error = "Tous les champs sont obligatoires";
            } else {
                $user = $this->userModel->verifyUser($username, $password);
                
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
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
        $success = '';
         if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($username) || empty($password) || empty($confirmPassword) || empty($email)) {
                $error = "Tous les champs sont obligatoires";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'email invalide";
            } elseif (strlen($password) < 6) {
                $error = "Le mot de passe doit contenir au moins 6 caractères";
            } elseif ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas";
            } else {
                try {
                    $this->userModel->createUser($username, $password, $email);
                    $_SESSION['registration_success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    header('Location: /login');
                    exit;
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        if (strpos($e->getMessage(), 'username') !== false) {
                           $error = "Ce nom d'utilisateur est déjà utilisé.";
                        } elseif (strpos($e->getMessage(), 'email') !== false) {
                           $error = "Cette adresse email est déjà utilisée.";
                        } else {
                           $error = "Erreur: Nom d'utilisateur ou email déjà existant.";
                        }
                    } else {
                        $error = "Une erreur est survenue lors de l'inscription: " . $e->getMessage();
                    }
                } catch (InvalidArgumentException $e) {
                     $error = $e->getMessage();
                }
            }
        }
        require_once __DIR__ . '/../Views/register.php';
    }
    
    public function logout() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function showForgotPasswordForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        require_once __DIR__ . '/../Views/forgot_password.php';
    }

    public function handleForgotPassword() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Veuillez fournir une adresse email valide.";
            } else {
                $user = $this->userModel->getUserByEmail($email);
                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    $tokenHash = hash('sha256', $token);
                    $expiresAt = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour

                    if ($this->userModel->storePasswordResetToken($user['id'], $tokenHash, $expiresAt)) {
                        $resetLink = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost:8080') . "/reset-password?token=" . $token;
                        
                        if ($this->emailNotification->sendPasswordResetEmail($user['id'], $user['email'], $resetLink)) {
                            $success = "Un email de réinitialisation de mot de passe a été envoyé à votre adresse.";
                        } else {
                            $error = "Impossible d'envoyer l'email de réinitialisation. Veuillez réessayer plus tard.";
                        }
                    } else {
                        $error = "Erreur lors de la génération du lien de réinitialisation. Veuillez réessayer.";
                    }
                } else {
                     $error = "Aucun compte n'est associé à cette adresse email.";
                }
            }
        }
        $_SESSION['forgot_password_error'] = $error;
        $_SESSION['forgot_password_success'] = $success;
        require_once __DIR__ . '/../Views/forgot_password.php';
    }

    public function showResetPasswordForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        $token = $_GET['token'] ?? '';
        $error = '';
        $validToken = false;

        if (empty($token)) {
            $error = "Token de réinitialisation manquant ou invalide.";
        } else {
            $tokenHash = hash('sha256', $token);
            $user = $this->userModel->getUserByResetToken($tokenHash);
            if ($user) {
                $validToken = true;
            } else {
                $error = "Token de réinitialisation invalide ou expiré.";
            }
        }
        $_SESSION['reset_password_error'] = $error; // Store error in session for display
        require_once __DIR__ . '/../Views/reset_password.php';
    }

    public function handleResetPassword() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        $error = '';
        $success = '';
        $token = $_POST['token'] ?? '';
        $validTokenForView = false; // To control if the form should be displayed

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($token)) {
                $error = "Token de réinitialisation manquant.";
            } elseif (empty($password) || empty($confirmPassword)) {
                $error = "Veuillez remplir tous les champs de mot de passe.";
            } elseif (strlen($password) < 6) {
                $error = "Le mot de passe doit contenir au moins 6 caractères.";
            } elseif ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas.";
            } else {
                $tokenHash = hash('sha256', $token);
                $user = $this->userModel->getUserByResetToken($tokenHash);
                if ($user) {
                    if ($this->userModel->updateUserPassword($user['id'], $password)) {
                        $_SESSION['login_success_message'] = "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.";
                        header('Location: /login');
                        exit;
                    } else {
                        $error = "Erreur lors de la mise à jour du mot de passe. Veuillez réessayer.";
                        $validTokenForView = true; // Keep showing the form
                    }
                } else {
                    $error = "Token de réinitialisation invalide ou expiré. Veuillez refaire une demande.";
                }
            }
        } else { // For GET request, just check token for view display
             $tokenHash = hash('sha256', $token);
             if ($this->userModel->getUserByResetToken($tokenHash)) {
                 $validTokenForView = true;
             } else if (!empty($token)) { // Only show error if token was provided but invalid
                 $error = "Token de réinitialisation invalide ou expiré.";
             } else if (empty($token) && !isset($_POST['token'])) { // No token at all
                 $error = "Token de réinitialisation manquant.";
             }
        }
        
        // Pass error and token validity to the view
        $_SESSION['reset_password_error'] = $error;
        // $validToken variable for the view to decide if it should render the form fields
        $validToken = $validTokenForView || (!empty($error) && $token && $this->userModel->getUserByResetToken(hash('sha256', $token)));
        require_once __DIR__ . '/../Views/reset_password.php';
    }
}