<?php
// Script de débogage pour tester l'envoi d'email

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Models/Database.php';
require_once __DIR__ . '/Models/EmailNotification.php';

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration de la base de données
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');

echo "<h1>Diagnostic de l'envoi d'email</h1>";

// Afficher la configuration actuelle
echo "<h2>Configuration actuelle</h2>";
echo "<pre>";
echo "PHP version: " . phpversion() . "\n";
echo "mail() function available: " . (function_exists('mail') ? 'Yes' : 'No') . "\n";
echo "SMTP_HOST: " . (getenv('SMTP_HOST') ?: 'Non défini (utilisant la valeur par défaut)') . "\n";
echo "SMTP_PORT: " . (getenv('SMTP_PORT') ?: 'Non défini (utilisant la valeur par défaut)') . "\n";
echo "SMTP_USERNAME: " . (getenv('SMTP_USERNAME') ? '******' : 'Non défini (utilisant la valeur par défaut)') . "\n";
echo "SMTP_PASSWORD: " . (getenv('SMTP_PASSWORD') ? '******' : 'Non défini (utilisant la valeur par défaut)') . "\n";
echo "FROM_EMAIL: " . (getenv('FROM_EMAIL') ?: 'Non défini (utilisant la valeur par défaut)') . "\n";
echo "FROM_NAME: " . (getenv('FROM_NAME') ?: 'Non défini (utilisant la valeur par défaut)') . "\n";
echo "</pre>";

try {
    // Connexion à la base de données
    $db = new Database($db_host, $db_name, $db_user, $db_pass);
    $pdo = $db->getConnection();
    
    echo "<p>Connexion à la base de données réussie.</p>";
    
    // Vérifier la table users et la colonne email
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email'");
    $columnExists = $stmt->fetch();
    
    if ($columnExists) {
        echo "<p>La colonne 'email' existe dans la table 'users'.</p>";
        
        // Afficher les emails des utilisateurs
        $stmt = $pdo->query("SELECT id, username, email FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h2>Utilisateurs et emails</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nom d'utilisateur</th><th>Email</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>La colonne 'email' n'existe PAS dans la table 'users'.</p>";
    }
    
    // Créer une instance de EmailNotification
    $emailNotification = new EmailNotification($pdo);
    
    // Tester l'envoi d'un email
    echo "<h2>Test d'envoi d'email</h2>";
    
    if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
        $userId = (int)$_GET['user_id'];
        
        // Récupérer l'utilisateur
        $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "<p>Tentative d'envoi d'un email à l'utilisateur: " . htmlspecialchars($user['username']) . " (" . htmlspecialchars($user['email']) . ")</p>";
            
            $testSubject = 'Test de notification ManegePark - Debug';
            $testMessage = '
                <h2>Ceci est un test de notification (debug)</h2>
                <p>Bonjour ' . htmlspecialchars($user['username']) . ',</p>
                <p>Ce message confirme que votre système de notification par email fonctionne correctement.</p>
                <p>Vous pouvez maintenant recevoir des alertes concernant l\'état de votre manège.</p>
                <p>Cordialement,<br>L\'équipe ManegePark</p>
            ';
            
            // Tester la fonction mail() directement
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: ManegePark Notifications <notifications@manegepark.com>',
                'Reply-To: notifications@manegepark.com',
                'X-Mailer: PHP/' . phpversion()
            ];
            
            $mailResult = mail($user['email'], $testSubject, $testMessage, implode("\r\n", $headers));
            
            echo "<p>Résultat de la fonction mail() : " . ($mailResult ? 'Succès' : 'Échec') . "</p>";
            
            // Tester via la classe EmailNotification
            $notificationResult = $emailNotification->sendNotification($userId, 'test', $testSubject, $testMessage);
            
            echo "<p>Résultat de EmailNotification::sendNotification() : " . ($notificationResult ? 'Succès' : 'Échec') . "</p>";
            
            // Afficher les logs d'email
            $logs = $emailNotification->getEmailLogs($userId, 5);
            
            echo "<h3>Derniers logs d'email pour cet utilisateur</h3>";
            if (count($logs) > 0) {
                echo "<table border='1'>";
                echo "<tr><th>ID</th><th>Type</th><th>Email</th><th>Sujet</th><th>Statut</th><th>Date</th></tr>";
                foreach ($logs as $log) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($log['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($log['event_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($log['email_to']) . "</td>";
                    echo "<td>" . htmlspecialchars($log['subject']) . "</td>";
                    echo "<td>" . htmlspecialchars($log['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($log['sent_at']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucun log trouvé.</p>";
            }
        } else {
            echo "<p style='color: red;'>Utilisateur non trouvé.</p>";
        }
    } else {
        echo "<p>Veuillez spécifier un ID d'utilisateur dans l'URL pour tester l'envoi d'email (par exemple: ?user_id=1)</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur de base de données: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
}
?> 