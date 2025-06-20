<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ManegePark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B00;
            --secondary-color: #0066CC;
            --accent-color: #FFD700;
            --dark-color: #333;
            --light-color: #fff;
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
                              url('https://images.unsplash.com/photo-1560306843-33986aebaf12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');
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
            font-size: 32px;
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
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s;
            background-color: rgba(255, 255, 255, 0.9);
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.2);
        }
        .input-icon {
            position: absolute;
            top: 42px;
            right: 15px;
            color: #999;
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
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        .btn:hover {
            background: linear-gradient(45deg, #FF9500, var(--primary-color));
            box-shadow: 0 6px 20px rgba(255, 107, 0, 0.5);
            transform: translateY(-2px);
        }
        .btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn:hover::after {
            left: 100%;
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
        .links-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .links-container a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .links-container a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e0e0e0;
        }
        .divider span {
            padding: 0 15px;
            color: #999;
            font-size: 14px;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
         .back-link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
        }
        .back-link a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        .logo {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span>MP</span>
        </div>
        <h1>Connexion</h1>
        
        <?php 
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['registration_success'])): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['registration_success']) ?>
            </div>
            <?php unset($_SESSION['registration_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['login_success_message'])): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['login_success_message']) ?>
            </div>
            <?php unset($_SESSION['login_success_message']); ?>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/login">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required>
                <i class="fas fa-user input-icon"></i>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                <i class="fas fa-lock input-icon"></i>
            </div>
            
            <button type="submit" class="btn">Se connecter <i class="fas fa-sign-in-alt"></i></button>
        </form>
        
        <div class="links-container">
            <a href="/forgot-password"><i class="fas fa-question-circle"></i> Mot de passe oublié ?</a>
        </div>

        <div class="divider">
            <span>OU</span>
        </div>
        
        <div style="text-align: center;">
            <a href="/register" style="color: var(--secondary-color); text-decoration: none; font-weight: 600;"><i class="fas fa-user-plus"></i> Créer un compte</a>
        </div>
        
        <div class="back-link">
            <a href="/"><i class="fas fa-home"></i> Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>