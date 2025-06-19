<?php

class ContactController {
    public function __construct() {
        // Vérifier si l'utilisateur est connecté
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function index() {
        require_once __DIR__ . '/../Views/contact.php';
    }
    
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $message = $_POST['message'] ?? '';
            
            // Validation simple
            $errors = [];
            if (empty($name)) {
                $errors[] = 'Le nom est requis';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'L\'adresse email est invalide';
            }
            if (empty($subject)) {
                $errors[] = 'Le sujet est requis';
            }
            if (empty($message)) {
                $errors[] = 'Le message est requis';
            }
            
            // S'il y a des erreurs, retourner à la page de contact avec les erreurs
            if (!empty($errors)) {
                $_SESSION['contact_errors'] = $errors;
                $_SESSION['contact_form_data'] = [
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message
                ];
                header('Location: /contact');
                exit;
            }
            
            // Envoyer l'email
            $to = 'contact@manegepark.com';
            $headers = [
                'From' => $email,
                'Reply-To' => $email,
                'X-Mailer' => 'PHP/' . phpversion()
            ];
            
            // Tentative d'envoi
            try {
                // Configuration pour l'envoi via SMTP
                $smtp_host = getenv('SMTP_HOST');
                $smtp_port = getenv('SMTP_PORT');
                
                // Pour débogage
                $debug = "Contact form debug: Tentative d'envoi à : $to via $smtp_host:$smtp_port\n";
                
                // Envoi direct via socket SMTP pour cette démo
                $socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, 30);
                if (!$socket) {
                    throw new Exception("Impossible de se connecter au serveur SMTP: $errstr ($errno)");
                }
                
                $response = fgets($socket, 515);
                fputs($socket, "HELO manegepark.com\r\n");
                $response = fgets($socket, 515);
                fputs($socket, "MAIL FROM:<$email>\r\n");
                $response = fgets($socket, 515);
                fputs($socket, "RCPT TO:<$to>\r\n");
                $response = fgets($socket, 515);
                fputs($socket, "DATA\r\n");
                $response = fgets($socket, 515);
                
                $email_content = "From: $name <$email>\r\n"
                    . "To: $to\r\n"
                    . "Subject: $subject\r\n\r\n"
                    . "Nouveau message de contact:\r\n"
                    . "Nom: $name\r\n"
                    . "Email: $email\r\n"
                    . "Message:\r\n$message\r\n";
                
                fputs($socket, $email_content . "\r\n.\r\n");
                $response = fgets($socket, 515);
                fputs($socket, "QUIT\r\n");
                fclose($socket);
                
                $debug .= "Message envoyé avec succès par connexion directe SMTP";
                error_log($debug);
                
                // Rediriger vers une page de confirmation
                $_SESSION['contact_success'] = true;
                header('Location: /eco-responsibility');  // Redirige vers une page existante
                exit;
                
            } catch (Exception $e) {
                $_SESSION['contact_errors'] = ["Erreur lors de l'envoi du message: " . $e->getMessage()];
                $_SESSION['contact_form_data'] = [
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message
                ];
                header('Location: /contact');
                exit;
            }
        } else {
            header('Location: /contact');
            exit;
        }
    }
} 