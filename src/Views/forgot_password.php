<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de Passe Oublié - ManegePark</title>
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
        input[type="email"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s;
        }
        input[type="email"]:focus {
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
        .success {
            color: #198754;
            background-color: rgba(25, 135, 84, 0.1);
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
        <h1>Mot de Passe Oublié</h1>
        <p style="text-align: center; color: #555; margin-bottom: 25px;">
            Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
        </p>

        <?php 
        if (session_status() === PHP_SESSION_NONE) session_start();
        $error = $_SESSION['forgot_password_error'] ?? null;
        $success = $_SESSION['forgot_password_success'] ?? null;
        unset($_SESSION['forgot_password_error'], $_SESSION['forgot_password_success']);
        ?>

        <?php if ($error): ?>
            <div class="message error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="message success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!$success): // Hide form if success message is shown ?>
        <form method="post" action="/forgot-password">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
                <i class="fas fa-envelope" style="position: absolute; top: 42px; right: 15px; color: #999;"></i>
            </div>
            <button type="submit" class="btn">Envoyer le lien <i class="fas fa-paper-plane"></i></button>
        </form>
        <?php endif; ?>

        <div class="login-link">
            <a href="/login"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
        </div>
    </div>
</body>
</html>