<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Écoresponsabilité - ManegePark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B00;
            --secondary-color: #0066CC;
            --accent-color: #4CAF50; /* Couleur verte pour l'écologie */
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
            border-top: 5px solid var(--accent-color);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            margin-top: 0;
            color: var(--accent-color);
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
        .eco-metric {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            background-color: #f0f8f0;
            border-left: 5px solid var(--accent-color);
        }
        .eco-metric-icon {
            font-size: 32px;
            margin-right: 20px;
            color: var(--accent-color);
        }
        .eco-metric-content {
            flex-grow: 1;
        }
        .eco-metric-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 5px 0;
        }
        .eco-metric-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent-color);
            margin: 0 0 5px 0;
        }
        .eco-metric-description {
            margin: 0;
            color: #666;
        }
        .eco-principles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .eco-principle {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            border-left: 5px solid var(--accent-color);
        }
        .eco-principle:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .eco-principle-title {
            display: flex;
            align-items: center;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px 0;
            color: var(--accent-color);
        }
        .eco-principle-title i {
            margin-right: 10px;
            font-size: 22px;
        }
        .eco-principle-content {
            color: #555;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--accent-color);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            margin-top: 20px;
            text-align: center;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            background-color: #3d9140;
        }
        .btn i {
            margin-right: 8px;
        }
        footer {
            background-color: #333;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
            text-align: center;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .back-link:hover {
            color: var(--primary-color);
        }
        .back-link i {
            margin-right: 8px;
        }
        /* Responsive styles */
        @media (max-width: 768px) {
            .eco-principles {
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
                    <i class="fas fa-leaf"></i>
                </div>
                <h1>ManegePark - Écoresponsable</h1>
            </div>
        </div>
    </header>
    
    <div class="container">
        <a href="/" class="back-link"><i class="fas fa-home"></i> Accueil</a>
        
        <?php if (isset($_SESSION['contact_success']) && !empty($_SESSION['contact_success'])): ?>
            <div class="alert success-alert" style="background-color: rgba(76, 175, 80, 0.1); border-left: 4px solid #4CAF50; color: #4CAF50; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                <i class="fas fa-check-circle" style="margin-right: 10px;"></i> <?= htmlspecialchars($_SESSION['contact_success']) ?>
            </div>
            <?php unset($_SESSION['contact_success']); // Effacer le message après l'avoir affiché ?>
        <?php endif; ?>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-leaf"></i> Notre Engagement Écoresponsable</h2>
            <p>
                Chez ManegePark, nous sommes engagés à réduire notre empreinte écologique et à contribuer 
                à un avenir plus durable. Notre plateforme a été conçue selon les principes du Green IT 
                pour minimiser notre impact environnemental tout en offrant une expérience utilisateur optimale.
            </p>
            <p>
                Découvrez ci-dessous nos initiatives écologiques et comment nous travaillons à rendre 
                notre parc d'attractions et notre système de gestion plus respectueux de l'environnement.
            </p>
        </div>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-chart-line"></i> Impact Environnemental</h2>
            
            <div class="eco-metric">
                <div class="eco-metric-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="eco-metric-content">
                    <h3 class="eco-metric-title">Consommation Énergétique</h3>
                    <p class="eco-metric-value">-45%</p>
                    <p class="eco-metric-description">Réduction de la consommation énergétique grâce à l'optimisation de notre infrastructure et la mise en veille automatique des attractions.</p>
                </div>
            </div>
            
            <div class="eco-metric">
                <div class="eco-metric-icon">
                    <i class="fas fa-cloud"></i>
                </div>
                <div class="eco-metric-content">
                    <h3 class="eco-metric-title">Émissions de CO2</h3>
                    <p class="eco-metric-value">-35%</p>
                    <p class="eco-metric-description">Réduction des émissions de carbone grâce à notre stratégie de développement durable et l'utilisation d'énergies renouvelables.</p>
                </div>
            </div>
            
            <div class="eco-metric">
                <div class="eco-metric-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="eco-metric-content">
                    <h3 class="eco-metric-title">Consommation d'Eau</h3>
                    <p class="eco-metric-value">-30%</p>
                    <p class="eco-metric-description">Système de récupération d'eau de pluie et optimisation des attractions aquatiques pour minimiser la consommation d'eau.</p>
                </div>
            </div>
        </div>
        
        <h2 style="color: var(--accent-color);"><i class="fas fa-seedling"></i> Nos Principes Écoresponsables</h2>
        
        <div class="eco-principles">
            <div class="eco-principle">
                <h3 class="eco-principle-title"><i class="fas fa-server"></i> Optimisation Serveur</h3>
                <p class="eco-principle-content">
                    Notre application utilise des serveurs éco-énergétiques et optimise les ressources informatiques 
                    pour réduire la consommation d'énergie. Nous utilisons des technologies de virtualisation et 
                    de mise à l'échelle automatique pour n'utiliser que les ressources nécessaires.
                </p>
            </div>
            
            <div class="eco-principle">
                <h3 class="eco-principle-title"><i class="fas fa-code"></i> Code Efficace</h3>
                <p class="eco-principle-content">
                    Notre code est optimisé pour minimiser l'utilisation du processeur et de la mémoire. 
                    Nous utilisons des pratiques de développement qui réduisent la complexité et améliorent 
                    les performances, ce qui se traduit par une empreinte écologique réduite.
                </p>
            </div>
            
            <div class="eco-principle">
                <h3 class="eco-principle-title"><i class="fas fa-database"></i> Gestion des Données</h3>
                <p class="eco-principle-content">
                    Nous appliquons des stratégies de gestion des données efficaces, comme l'archivage 
                    intelligent et la compression, pour réduire le stockage nécessaire et l'énergie 
                    consommée pour maintenir ces données.
                </p>
            </div>
            
            <div class="eco-principle">
                <h3 class="eco-principle-title"><i class="fas fa-mobile-alt"></i> Conception Responsive</h3>
                <p class="eco-principle-content">
                    Notre interface est conçue pour s'adapter à tous les appareils, ce qui réduit le besoin 
                    d'applications spécifiques à chaque plateforme et minimise la duplication des ressources.
                </p>
            </div>
            
            <div class="eco-principle">
                <h3 class="eco-principle-title"><i class="fas fa-recycle"></i> Économie Circulaire</h3>
                <p class="eco-principle-content">
                    Nous appliquons les principes de l'économie circulaire à notre infrastructure informatique, 
                    en privilégiant la réparation et le reconditionnement des équipements plutôt que leur remplacement.
                </p>
            </div>
            
            <div class="eco-principle">
                <h3 class="eco-principle-title"><i class="fas fa-lightbulb"></i> Éclairage Intelligent</h3>
                <p class="eco-principle-content">
                    Notre système de gestion contrôle l'éclairage du parc en fonction de la lumière naturelle 
                    et de la présence de visiteurs, permettant d'économiser jusqu'à 60% d'énergie dédiée à l'éclairage.
                </p>
            </div>
        </div>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-hand-holding-heart"></i> Notre Engagement pour l'Avenir</h2>
            <p>
                Nous nous engageons à améliorer continuellement notre impact environnemental. Nos objectifs pour les prochaines années incluent :
            </p>
            <ul>
                <li>Atteindre la neutralité carbone d'ici 2025</li>
                <li>Réduire notre consommation d'eau de 50% par rapport à 2020</li>
                <li>Alimenter 100% de nos attractions par des énergies renouvelables</li>
                <li>Éliminer complètement les déchets plastiques à usage unique dans notre parc</li>
                <li>Développer un programme de formation écologique pour tous nos employés</li>
            </ul>
            <p>
                Nous croyons que le divertissement et le respect de l'environnement peuvent aller de pair. 
                Rejoignez-nous dans cette aventure vers un avenir plus durable !
            </p>
            
            <a href="/contact" class="btn"><i class="fas fa-envelope"></i> Contactez-nous pour en savoir plus</a>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?= date('Y') ?> ManegePark - Tous droits réservés</p>
        <p>Un parc d'attractions écoresponsable</p>
    </footer>
</body>
</html> 