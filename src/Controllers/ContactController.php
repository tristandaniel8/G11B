<?php

class ContactController {
    
    /**
     * Affiche le formulaire de contact
     */
    public function index() {
        require_once __DIR__ . '/../Views/contact.php';
    }
    
    /**
     * Traite l'envoi du formulaire de contact
     */
    public function submit() {
        $success = false;
        $message = '';
        $debug = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $message_content = $_POST['message'] ?? '';
            
            // Validation des champs
            if (empty($name) || empty($email) || empty($subject) || empty($message_content)) {
                $message = 'Tous les champs sont obligatoires.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = 'Adresse email invalide.';
            } else {
                // Configuration pour envoyer à MailHog
                $to = 'contact@manegepark.com'; // Adresse de destination
                $emailSubject = '[Contact ManegePark] ' . $subject;
                
                // Formater le message HTML
                $htmlMessage = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Nouveau message de contact</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            line-height: 1.6;
                            color: #333;
                            background-color: #f9f9f9;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            background-color: #ffffff;
                            border: 1px solid #e0e0e0;
                            border-radius: 5px;
                        }
                        .header {
                            background: linear-gradient(135deg, #FF6B00, #0066CC);
                            color: white;
                            padding: 20px;
                            text-align: center;
                            border-radius: 5px 5px 0 0;
                            margin: -20px -20px 20px;
                        }
                        .content {
                            padding: 20px;
                        }
                        .footer {
                            text-align: center;
                            margin-top: 20px;
                            font-size: 12px;
                            color: #777;
                        }
                        .field {
                            margin-bottom: 15px;
                        }
                        .field-label {
                            font-weight: bold;
                            color: #555;
                        }
                        .field-value {
                            margin-top: 5px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>Nouveau message de contact</h1>
                        </div>
                        <div class="content">
                            <div class="field">
                                <div class="field-label">Nom :</div>
                                <div class="field-value">' . htmlspecialchars($name) . '</div>
                            </div>
                            <div class="field">
                                <div class="field-label">Email :</div>
                                <div class="field-value">' . htmlspecialchars($email) . '</div>
                            </div>
                            <div class="field">
                                <div class="field-label">Objet :</div>
                                <div class="field-value">' . htmlspecialchars($subject) . '</div>
                            </div>
                            <div class="field">
                                <div class="field-label">Message :</div>
                                <div class="field-value">' . nl2br(htmlspecialchars($message_content)) . '</div>
                            </div>
                        </div>
                        <div class="footer">
                            <p>&copy; ' . date('Y') . ' ManegePark - Formulaire de contact</p>
                        </div>
                    </div>
                </body>
                </html>';
                
                // Utilisation de l'API PHP mail() avec les variables d'environnement Docker
                $smtpHost = getenv('SMTP_HOST') ?: 'mailhog';
                $smtpPort = getenv('SMTP_PORT') ?: '1025';
                
                // En-têtes pour l'email
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                $headers .= "From: " . htmlspecialchars($name) . " <" . htmlspecialchars($email) . ">\r\n";
                $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                
                // Envoyer le message directement via socket à MailHog (méthode alternative sans sendmail)
                try {
                    // Ouvrir une connexion socket à MailHog
                    $socket = @fsockopen($smtpHost, $smtpPort, $errno, $errstr, 30);
                    
                    if (!$socket) {
                        throw new Exception("Impossible de se connecter au serveur SMTP : $errstr ($errno)");
                    }
                    
                    // Attendre la réponse initiale du serveur SMTP
                    $this->getSmtpResponse($socket);
                    
                    // Envoyer la commande HELO
                    fwrite($socket, "HELO localhost\r\n");
                    $this->getSmtpResponse($socket);
                    
                    // Envoyer l'expéditeur
                    fwrite($socket, "MAIL FROM:<" . htmlspecialchars($email) . ">\r\n");
                    $this->getSmtpResponse($socket);
                    
                    // Envoyer le destinataire
                    fwrite($socket, "RCPT TO:<$to>\r\n");
                    $this->getSmtpResponse($socket);
                    
                    // Commencer l'envoi des données
                    fwrite($socket, "DATA\r\n");
                    $this->getSmtpResponse($socket);
                    
                    // Envoyer les en-têtes et le contenu
                    $fullMessage = "Subject: $emailSubject\r\n";
                    $fullMessage .= $headers . "\r\n";
                    $fullMessage .= $htmlMessage . "\r\n";
                    $fullMessage .= ".\r\n"; // Terminer les données
                    
                    fwrite($socket, $fullMessage);
                    $this->getSmtpResponse($socket);
                    
                    // Fermer la connexion
                    fwrite($socket, "QUIT\r\n");
                    fclose($socket);
                    
                    $success = true;
                    $message = 'Votre message a été envoyé avec succès. Nous vous contacterons bientôt.';
                    
                    // Journaliser les informations de débogage
                    $debug = "Tentative d'envoi à : $to via $smtpHost:$smtpPort\n";
                    $debug .= "Message envoyé avec succès par connexion directe SMTP";
                    
                    // Redirection vers la page d'écoresponsabilité après envoi réussi
                    $_SESSION['contact_success'] = $message;
                    header('Location: /eco-responsibility');
                    exit;
                    
                } catch (Exception $e) {
                    $message = 'Une erreur est survenue lors de l\'envoi du message : ' . $e->getMessage();
                    $debug = "Erreur SMTP : " . $e->getMessage();
                }
                
                // Journaliser les erreurs dans un fichier pour débogage
                error_log("Contact form debug: $debug", 0);
            }
        }
        
        // Inclure la vue avec les résultats
        require_once __DIR__ . '/../Views/contact.php';
    }
    
    /**
     * Récupère et vérifie la réponse SMTP
     */
    private function getSmtpResponse($socket) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            // Vérifier si la ligne commence par le code d'état et un espace (réponse terminée)
            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }
        
        $code = substr($response, 0, 3);
        if ($code < 200 || $code >= 400) {
            throw new Exception("Erreur SMTP : $response");
        }
        
        return $response;
    }
} 