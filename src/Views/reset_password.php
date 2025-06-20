<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser Mot de Passe - ManegePark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <style>
        :root {
            --primary-color: #FF6B00;
            --secondary-color: #0066CC;
            --dark-color: #333;
        }
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: linear-gradient(135deg, rgba(255, 107, 0, 0.1) 0%, rgba(0, 102, 204, 0.1) 100%), 
                              url('https://images.unsplash.com/photo-1560306843-33986aebaf12?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 400px;
            max-width: 90%;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }
        input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s;
        }
        input[type="password"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.2);
        }
        .btn {
            background: linear-gradient(45deg, var(--primary-color), #FF9500);
            color: white;
            padding: 15px 20px;
            border: none;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        .error {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo"><span>MP</span></div>
        <h1>Réinitialiser Votre Mot de Passe</h1>

        <?php
        if (session_status() === PHP_SESSION_NONE) session_start();
        $error = $_SESSION['reset_password_error'] ?? null; // Get error from session
        unset($_SESSION['reset_password_error']); // Clear it after displaying
        $token = $_GET['token'] ?? ($_POST['token'] ?? ''); // Get token from GET or POST
        $validToken = $validToken ?? false; // This should be set by the controller
        
        // Controller should set $validToken. If not, re-check for safety, but prefer controller logic.
        if (!$validToken && !empty($token) && !$error) {
             // Basic check if controller didn't set $validToken, you might need more robust check
             // This is just a fallback, AuthController should manage $validToken state
             $tokenHash = hash('sha256', $token);
             // Assume $userModel would be available through some means or this check is simplified/removed
             // For now, we rely on error message from controller if token is invalid.
        }
        ?>

        <?php if ($error): ?>
            <div class="message error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($validToken && !$error) : // Only show form if token is valid and no overriding error ?>
            <form method="post" action="/reset-password">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Entrez votre nouveau mot de passe" required>
                    <i class="fas fa-lock" style="position: absolute; top: 42px; right: 15px; color: #999;"></i>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez votre nouveau mot de passe" required>
                    <i class="fas fa-lock" style="position: absolute; top: 42px; right: 15px; color: #999;"></i>
                </div>
                <button type="submit" class="btn">Réinitialiser <i class="fas fa-key"></i></button>
            </form>
        <?php elseif(!$error) : // If no error but token is not valid (e.g. initial GET without token) ?>
             <div class="message error"><i class="fas fa-exclamation-circle"></i> Token invalide ou manquant.</div>
        <?php endif; ?>

        <div class="login-link">
            <a href="/login"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
        </div>
    </div>
</body>
</html>