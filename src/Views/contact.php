<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - ManegePark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B00;
            --secondary-color: #0066CC;
            --accent-color: #4CAF50;
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
        .navigation {
            margin-top: 20px;
            text-align: center;
        }
        .navigation a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .navigation a:hover {
            color: var(--accent-color);
        }
        .card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
            transition: all 0.3s;
            border-top: 5px solid var(--primary-color);
        }
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .card-title {
            margin-top: 0;
            color: var(--primary-color);
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            font-size: 24px;
            display: flex;
            align-items: center;
        }
        .card-title i {
            margin-right: 10px;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.1);
        }
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        .btn {
            background: linear-gradient(45deg, var(--primary-color), #FF9500);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
            box-shadow: 0 4px 10px rgba(255, 107, 0, 0.2);
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(255, 107, 0, 0.3);
        }
        .btn i {
            margin-right: 8px;
        }
        .contact-info {
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }
        .contact-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        .contact-info-icon {
            font-size: 24px;
            color: var(--primary-color);
            margin-right: 15px;
            min-width: 30px;
            text-align: center;
        }
        .contact-info-content h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: var(--dark-color);
        }
        .contact-info-content p {
            margin: 0;
            color: #666;
        }
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .social-link {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            text-decoration: none;
        }
        .social-link:hover {
            transform: translateY(-3px);
            background-color: var(--secondary-color);
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .back-link:hover {
            color: var(--secondary-color);
        }
        .back-link i {
            margin-right: 8px;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            border-left: 4px solid var(--success-color);
            color: var(--success-color);
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid var(--danger-color);
            color: var(--danger-color);
        }
        footer {
            background-color: #333;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
            text-align: center;
        }
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            .header-title {
                margin-bottom: 15px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-title">
                <div class="header-logo">
                    <i class="fas fa-envelope"></i>
                </div>
                <h1>Contactez-nous</h1>
            </div>
        </div>
    </header>
    
    <div class="container">
        <a href="/" class="back-link"><i class="fas fa-home"></i> Accueil</a>
        
        <?php if (isset($message) && !empty($message)): ?>
            <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($debug) && !empty($debug) && isset($_GET['debug'])): ?>
            <div class="alert" style="background-color: #f8f9fa; border-left: 4px solid #6c757d; color: #6c757d;">
                <h4>Informations de débogage</h4>
                <pre style="white-space: pre-wrap;"><?= htmlspecialchars($debug) ?></pre>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-paper-plane"></i> Envoyez-nous un message</h2>
            <p>Avez-vous des questions sur nos initiatives écoresponsables ou souhaitez-vous en savoir plus sur ManegePark ? N'hésitez pas à nous contacter, nous vous répondrons dans les plus brefs délais.</p>
            
            <div class="contact-grid">
                <div>
                    <form action="/contact/submit" method="post">
                        <div class="form-group">
                            <label for="name">Nom complet</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Objet</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
                    </form>
                </div>
                
                <div class="contact-info">
                    <h3>Informations de contact</h3>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-info-content">
                            <h3>Adresse</h3>
                            <p>123 Avenue des Attractions<br>75001 Paris, France</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-info-content">
                            <h3>Téléphone</h3>
                            <p>+33 1 23 45 67 89</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-info-content">
                            <h3>Email</h3>
                            <p>contact@manegepark.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-info-content">
                            <h3>Heures d'ouverture</h3>
                            <p>Lun - Ven: 9h00 - 18h00<br>Sam - Dim: 10h00 - 17h00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?= date('Y') ?> ManegePark - Tous droits réservés</p>
        <p>Un parc d'attractions écoresponsable</p>
    </footer>
</body>
</html> 