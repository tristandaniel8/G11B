<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManegePark - Système de Gestion Manège</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B00;
            --secondary-color: #0066CC;
            --accent-color: #FFD700;
            --eco-color: #4CAF50; /* Nouvelle couleur pour l'écoresponsabilité */
            --dark-color: #333;
            --light-color: #fff;
        }
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f0f0f0;
        }
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1513889961551-628c1e5e2ee9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 80px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 100'%3E%3Cpath fill='%23f0f0f0' fill-opacity='1' d='M0,64L60,58.7C120,53,240,43,360,48C480,53,600,75,720,80C840,85,960,75,1080,64C1200,53,1320,43,1380,37.3L1440,32L1440,100L1380,100C1320,100,1200,100,1080,100C960,100,840,100,720,100C600,100,480,100,360,100C240,100,120,100,60,100L0,100Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            background-repeat: no-repeat;
        }
        .hero h1 {
            font-size: 48px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
        }
        .hero p {
            font-size: 20px;
            max-width: 800px;
            margin: 0 auto 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            flex: 1;
        }
        .logo {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        .logo::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.5);
            border-radius: 50%;
        }
        .btn {
            background: linear-gradient(45deg, var(--primary-color), #FF9500);
            color: white;
            padding: 15px 35px;
            border: none;
            font-size: 18px;
            cursor: pointer;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.4);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(255, 107, 0, 0.6);
        }
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
            z-index: -1;
        }
        .btn:hover::before {
            left: 100%;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 50px 0;
        }
        .feature {
            background-color: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .feature::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            z-index: -1;
            transform: scale(0);
            transition: transform 0.4s;
            border-radius: 20px;
        }
        .feature:hover {
            transform: translateY(-10px);
            color: white;
        }
        .feature:hover::before {
            transform: scale(1);
        }
        .feature:hover h3, .feature:hover p, .feature:hover .feature-icon {
            color: white;
            position: relative;
            z-index: 2;
        }
        .feature h3 {
            color: var(--primary-color);
            margin-top: 0;
            font-size: 24px;
            transition: color 0.3s;
        }
        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: var(--secondary-color);
            transition: color 0.3s;
        }
        .feature p {
            transition: color 0.3s;
            line-height: 1.6;
        }
        /* Section écoresponsable */
        .eco-section {
            background-color: #f5f9f5;
            margin: 50px -20px;
            padding: 60px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .eco-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--eco-color), #8BC34A);
        }
        .eco-section h2 {
            color: var(--eco-color);
            font-size: 32px;
            margin-bottom: 20px;
        }
        .eco-section p {
            max-width: 800px;
            margin: 0 auto 30px;
            color: #444;
            font-size: 18px;
            line-height: 1.6;
        }
        .eco-badges {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .eco-badge {
            background-color: white;
            border-radius: 50px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        .eco-badge i {
            color: var(--eco-color);
            font-size: 24px;
            margin-right: 10px;
        }
        .eco-badge span {
            font-weight: 600;
            color: #444;
        }
        .btn-eco {
            background: linear-gradient(45deg, var(--eco-color), #8BC34A);
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        }
        .btn-eco:hover {
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
        }
        footer {
            background-color: var(--dark-color);
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: auto;
            position: relative;
        }
        footer::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 0;
            width: 100%;
            height: 80px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 100'%3E%3Cpath fill='%23f0f0f0' fill-opacity='1' d='M0,32L60,37.3C120,43,240,53,360,64C480,75,600,85,720,80C840,75,960,53,1080,48C1200,43,1320,53,1380,58.7L1440,64L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            background-repeat: no-repeat;
        }
        .footer-content {
            padding-top: 60px;
        }
        .status {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .status.success {
            border-left: 5px solid var(--accent-color);
        }
        .status.error {
            border-left: 5px solid #dc3545;
        }
        .social-icons {
            margin-top: 20px;
        }
        .social-icons a {
            color: white;
            font-size: 24px;
            margin: 0 10px;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: var(--accent-color);
        }
        
        /* Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .logo {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }
            .hero p {
                font-size: 16px;
                padding: 0 20px;
            }
            .features {
                grid-template-columns: 1fr;
            }
            .eco-badges {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="logo">
            <span>MP</span>
        </div>
        <h1>ManegePark</h1>
        <p>Plateforme de sécurité pour le contrôle et la surveillance des manèges du parc d'attractions</p>
        <a href="/login" class="btn">Connexion <i class="fas fa-arrow-right"></i></a>
    </div>
    
    <div class="container">
        <div class="features">
            <div class="feature">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Sécurité Maximale</h3>
                <p>Vérifiez que tous les passagers sont correctement installés avant de démarrer le manège pour une expérience sans risque.</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Surveillance en Temps Réel</h3>
                <p>Suivez en temps réel l'état du bouton "Prêt", du moteur, de la LED et du potentiomètre pour une gestion optimale.</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon"><i class="fas fa-sliders-h"></i></div>
                <h3>Contrôle Intuitif</h3>
                <p>Contrôlez manuellement les actionneurs ou laissez le système automatiser les opérations pour une expérience utilisateur fluide.</p>
            </div>
        </div>
        
        <!-- Nouvelle section écoresponsable -->
        <div class="eco-section">
            <h2><i class="fas fa-leaf"></i> ManegePark Écoresponsable</h2>
            <p>Notre engagement pour l'environnement va au-delà des mots. Découvrez comment nous avons conçu notre parc et notre système de gestion pour minimiser notre impact écologique tout en offrant une expérience exceptionnelle.</p>
            
            <div class="eco-badges">
                <div class="eco-badge">
                    <i class="fas fa-bolt"></i>
                    <span>-45% de consommation énergétique</span>
                </div>
                <div class="eco-badge">
                    <i class="fas fa-cloud"></i>
                    <span>-35% d'émissions de CO2</span>
                </div>
                <div class="eco-badge">
                    <i class="fas fa-tint"></i>
                    <span>-30% de consommation d'eau</span>
                </div>
            </div>
            
            <a href="/eco-responsibility" class="btn btn-eco">Découvrir nos initiatives <i class="fas fa-leaf"></i></a>
        </div>
        
        <?php if (isset($db_status)): ?>
            <div class="status <?= $connection_status ? 'success' : 'error' ?>">
                <?= htmlspecialchars($db_status) ?>
            </div>
        <?php endif; ?>
    </div>
    
    <footer>
        <div class="footer-content">
            <p>&copy; <?= date('Y') ?> ManegePark - Système de Gestion Manège</p>
            <p><a href="/eco-responsibility" style="color: #4CAF50; text-decoration: none;"><i class="fas fa-leaf"></i> Un parc écoresponsable</a></p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>
