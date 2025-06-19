<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - ManegePark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B00;
            --secondary-color: #0066CC;
            --accent-color: #FFD700;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --dark-color: #333;
            --light-color: #fff;
            --bg-color: #f8f9fa;
        }
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--dark-color);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path d="M0 0L100 0L100 100Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 100px 100px;
            opacity: 0.2;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }
        .header-title {
            display: flex;
            align-items: center;
        }
        .header-logo {
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary-color);
            font-weight: bold;
            margin-right: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }
        .header-logo i {
            font-size: 28px;
        }
        nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            margin-left: 20px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        nav ul li a:hover {
            color: var(--accent-color);
            transform: translateY(-2px);
        }
        nav ul li a i {
            margin-right: 8px;
            font-size: 16px;
        }
        .contact-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
            border-top: 5px solid var(--primary-color);
            transition: all 0.3s;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .contact-card h2 {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            font-size: 24px;
            display: flex;
            align-items: center;
        }
        .contact-card h2 i {
            margin-right: 10px;
            font-size: 24px;
        }
        .contact-form .form-group {
            margin-bottom: 20px;
        }
        .contact-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }
        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }
        .contact-form textarea {
            min-height: 150px;
            resize: vertical;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            font-size: 16px;
        }
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), #FF9500);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 107, 0, 0.4);
        }
        .btn i {
            margin-right: 8px;
        }
        .error-message {
            background-color: #f8d7da;
            border-left: 4px solid var(--danger-color);
            color: #721c24;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error-message ul {
            margin: 5px 0;
            padding-left: 20px;
        }
        .contact-info {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .contact-method {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        .contact-method:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .contact-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 20px;
        }
        .contact-details h3 {
            margin: 0 0 5px 0;
            color: var(--dark-color);
            font-size: 18px;
        }
        .contact-details p {
            margin: 0;
            color: #666;
        }
        .contact-details a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: all 0.3s;
        }
        .contact-details a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .footer-logo {
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        .footer-logo i {
            color: var(--primary-color);
            font-size: 28px;
            margin-right: 10px;
        }
        .footer-links {
            display: flex;
        }
        .footer-links a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        .footer-links a:hover {
            color: var(--primary-color);
        }
        .footer-links a i {
            margin-right: 5px;
        }
        @media (max-width: 768px) {
            .header-content, .footer-content {
                flex-direction: column;
                text-align: center;
            }
            .header-title {
                margin-bottom: 15px;
                justify-content: center;
            }
            nav ul {
                justify-content: center;
            }
            .footer-logo {
                margin-bottom: 20px;
            }
            .footer-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            .footer-links a {
                margin: 5px 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-title">
                <div class="header-logo">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h1>ManegePark</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="/"><i class="fas fa-home"></i> Accueil</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="/dashboard"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                        <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="/login"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
                    <?php endif; ?>
                    <li><a href="/eco-responsibility"><i class="fas fa-leaf"></i> Écoresponsabilité</a></li>
                    <li><a href="/contact"><i class="fas fa-envelope"></i> Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <div class="contact-card">
            <h2><i class="fas fa-paper-plane"></i> Contactez-nous</h2>
            
            <?php if (isset($_SESSION['contact_errors'])): ?>
                <div class="error-message">
                    <strong>Erreur(s) :</strong>
                    <ul>
                        <?php foreach ($_SESSION['contact_errors'] as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['contact_errors']); ?>
            <?php endif; ?>
            
            <form class="contact-form" action="/contact/submit" method="post">
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($_SESSION['contact_form_data']['name'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['contact_form_data']['email'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Sujet</label>
                    <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($_SESSION['contact_form_data']['subject'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required><?= htmlspecialchars($_SESSION['contact_form_data']['message'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Envoyer le message</button>
            </form>
            
            <?php unset($_SESSION['contact_form_data']); ?>
            
            <div class="contact-info">
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Email</h3>
                        <p><a href="mailto:contact@manegepark.com">contact@manegepark.com</a></p>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Téléphone</h3>
                        <p><a href="tel:+33123456789">+33 (0)1 23 45 67 89</a></p>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-details">
                        <h3>Adresse</h3>
                        <p>123 Avenue du Parc, 75001 Paris, France</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <i class="fas fa-ticket-alt"></i>
                ManegePark
            </div>
            <div class="footer-links">
                <a href="/"><i class="fas fa-home"></i> Accueil</a>
                <a href="/eco-responsibility"><i class="fas fa-leaf"></i> Écoresponsabilité</a>
                <a href="/contact"><i class="fas fa-envelope"></i> Contact</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/dashboard"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                <?php else: ?>
                    <a href="/login"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</body>
</html> 