<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - ManegePark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .header-title { display: flex; align-items: center; }
        .header-logo {
            width: 50px; height: 50px; background-color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: var(--primary-color); font-weight: bold;
            margin-right: 15px; box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        .header-logo i { font-size: 28px; }
        .user-info { display: flex; align-items: center; flex-wrap: wrap; }
        .user-info span { margin-right: 15px; font-weight: 500; display: flex; align-items: center; margin-bottom: 5px; }
        .user-info span i { margin-right: 8px; font-size: 18px; }
        .logout-btn, .nav-link {
            background-color: rgba(255, 255, 255, 0.2); border: none; color: white;
            padding: 10px 15px; border-radius: 50px; cursor: pointer; text-decoration: none;
            font-weight: 600; display: flex; align-items: center; transition: all 0.3s; margin-left: 10px; margin-bottom:5px;
        }
        .logout-btn i, .nav-link i { margin-right: 8px; }
        .logout-btn:hover, .nav-link:hover { background-color: rgba(255,255,255,0.3); transform: translateY(-2px); }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .card {
            background-color: white; border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); padding: 25px;
            transition: all 0.3s; border-top: 5px solid var(--primary-color);
            display: flex; flex-direction: column; /* For footer alignment */
        }
        .card-content { flex-grow: 1; } /* For footer alignment */
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .card-title {
            margin-top: 0; color: var(--primary-color); border-bottom: 1px solid #eee;
            padding-bottom: 15px; font-size: 20px; display: flex; align-items: center;
        }
        .card-title i { margin-right: 10px; font-size: 24px; }
        
        .status-indicator {
            display: flex; align-items: center; margin-bottom: 20px;
            padding: 15px; border-radius: 10px; background-color: #f8f9fa;
        }
        .status-circle {
            width: 20px; height: 20px; border-radius: 50%; margin-right: 12px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .status-on { background-color: var(--success-color); animation: pulse 2s infinite; }
        .status-off { background-color: var(--danger-color); }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(40,167,69,0.7); } 70% { box-shadow: 0 0 0 10px rgba(40,167,69,0); } 100% { box-shadow: 0 0 0 0 rgba(40,167,69,0); } }
        .status-text { font-weight: 600; font-size: 18px; }

        .slider-container { margin-top: 15px; }
        .slider {
            width: 100%; margin-bottom: 10px; -webkit-appearance: none; appearance: none;
            height: 10px; border-radius: 5px; background: #e0e0e0; outline: none;
        }
        .slider::-webkit-slider-thumb {
            -webkit-appearance: none; appearance: none; width: 22px; height: 22px;
            border-radius: 50%; background: var(--primary-color); cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: all 0.3s;
        }
        .slider::-webkit-slider-thumb:hover { transform: scale(1.1); }
        .slider-value-display { font-weight: bold; color: var(--primary-color); font-size: 20px; margin-bottom: 10px; }
        
        .btn {
            padding: 10px 18px; border: none; border-radius: 50px; cursor: pointer;
            font-weight: 600; display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.3s; box-shadow: 0 3px 10px rgba(0,0,0,0.1); text-decoration:none;
        }
        .btn i { margin-right: 8px; }
        .btn-primary { background: linear-gradient(45deg, var(--primary-color), #FF9500); color: white; }
        .btn-secondary { background: linear-gradient(45deg, var(--gray-color), #5a6268); color: black;}
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

        
        .status-on-custom-green { /* For temperature > 37°C */
            background-color: var(--success-color); /* Green */
            animation: pulse-green 2s infinite;
        }
        .status-off-custom-red { /* For temperature <= 37°C */
            background-color: var(--danger-color); /* Red */
        }

        @keyframes pulse-green { /* Re-using or make a new pulse if colors differ significantly */
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        
        .card-footer { margin-top: auto; padding-top: 15px; border-top: 1px solid #eee; text-align: right;}


        .weather-card { border-top-color: var(--secondary-color) !important; }
        .weather-container { display: flex; flex-direction: column; gap: 15px; }
        .weather-main { display: flex; align-items: center; background-color: rgba(0,102,204,0.05); padding: 15px; border-radius: 10px; }
        .weather-icon img { width: 70px; height: 70px; margin-right: 15px; }
        .weather-info { flex-grow: 1; }
        .weather-temp { font-size: 28px; font-weight: 700; color: var(--secondary-color); }
        .weather-desc { font-size: 16px; margin-bottom: 5px; }
        .weather-city { font-size: 14px; color: #666; }
        .weather-details { display: flex; justify-content: space-around; text-align: center; background-color: #f8f9fa; padding: 10px; border-radius: 10px; margin-top:10px;}
        .weather-detail { display: flex; flex-direction: column; align-items: center; }
        .weather-detail i { font-size: 18px; color: var(--secondary-color); margin-bottom: 5px; }
        .weather-detail span { font-size: 16px; font-weight: 600; }
        .weather-detail small { font-size: 11px; color: #666; }
        .weather-city-form { margin-top: 15px; }
        .input-group { display: flex; border-radius: 50px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .weather-city-input { flex-grow: 1; padding: 10px 15px; border: none; outline: none; font-size: 14px; }
        .btn-sm { padding: 8px 15px; font-size: 14px; }
        .weather-error-message {
            background-color: rgba(220, 53, 69, 0.1); color: var(--danger-color);
            padding: 10px; border-radius: 8px; text-align: center; font-weight: 500;
        }

        @media (max-width: 768px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .header-content { flex-direction: column; text-align: center; }
            .header-title { margin-bottom: 15px; }
            .user-info { flex-direction: column; align-items: center; }
            .user-info span { margin: 0 0 10px 0; }
            .logout-btn, .nav-link { margin: 5px 0; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-title">
                <div class="header-logo"><i class="fas fa-tachometer-alt"></i></div>
                <h1>Tableau de Bord</h1>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user-circle"></i> Bienvenue, <?= htmlspecialchars($_SESSION['username'] ?? 'Visiteur') ?></span>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="/admin" class="nav-link"><i class="fas fa-users-cog"></i> Administration</a>
                <?php endif; ?>
                <a href="/email-notifications" class="nav-link"><i class="fas fa-bell"></i> Notifications</a>
                <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="dashboard-grid">
            
            <div class="card">
                <div class="card-content">
                    <h2 class="card-title"><i class="fas fa-cogs"></i> Contrôle du Moteur</h2>
                    <div class="status-indicator">
                        <div class="status-circle <?= ($currentMotorSpeed > 0) ? 'status-on' : 'status-off' ?>"></div>
                        <span class="status-text">Vitesse Actuelle: <span id="current-speed-display"><?= htmlspecialchars($currentMotorSpeed) ?></span>/10</span>
                    </div>
                    <div class="slider-container">
                        <label for="motor-speed-slider" style="display:block; margin-bottom: 8px; font-weight:600;">Régler la vitesse (0-10):</label>
                        <input type="range" min="0" max="10" step="1" value="<?= htmlspecialchars($currentMotorSpeed) ?>" class="slider" id="motor-speed-slider">
                        <div class="slider-value-display">Vitesse sélectionnée: <span id="slider-speed-value"><?= htmlspecialchars($currentMotorSpeed) ?></span></div>
                        <button class="btn btn-primary" onclick="updateMotorSpeed()"><i class="fas fa-check"></i> Appliquer la Vitesse</button>
                    </div>
                </div>
                <div class="card-footer">
                     <a href="/history" class="btn btn-secondary"><i class="fas fa-history"></i> Voir l'Historique</a>
                </div>
            </div>

            <div class="card weather-card">
                <div class="card-content">
                    <h2 class="card-title"><i class="fas fa-cloud-sun"></i> Météo</h2>
                    <?php if (isset($weatherData['error'])): ?>
                        <div class="weather-error-message">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($weatherData['error']) ?>
                        </div>
                        <?php if (strpos($weatherData['error'], '404') !== false || strpos($weatherData['error'], 'Impossible') !== false ): ?>
                            <p style="text-align:center; margin-top:10px; color: #555;">Veuillez vérifier le nom de la ville et réessayer.</p>
                        <?php endif; ?>
                    <?php else: ?>
                    <div class="weather-container">
                        <div class="weather-main">
                            <?php if (isset($weatherData['icon'])): ?>
                            <img src="https://openweathermap.org/img/wn/<?= htmlspecialchars($weatherData['icon']) ?>@2x.png" alt="Icône météo" class="weather-icon">
                            <?php endif; ?>
                            <div class="weather-info">
                                <div class="weather-temp"><?= isset($weatherData['temperature']) ? htmlspecialchars($weatherData['temperature']) . '°C' : 'N/A' ?></div>
                                <div class="weather-desc"><?= isset($weatherData['description']) ? htmlspecialchars($weatherData['description']) : 'N/A' ?></div>
                                <div class="weather-city"><?= isset($weatherData['city']) ? htmlspecialchars($weatherData['city']) : 'N/A' ?></div>
                            </div>
                        </div>
                        <div class="weather-details">
                            <div class="weather-detail">
                                <i class="fas fa-tint"></i>
                                <span><?= isset($weatherData['humidity']) ? htmlspecialchars($weatherData['humidity']) . '%' : 'N/A' ?></span>
                                <small>Humidité</small>
                            </div>
                            <div class="weather-detail">
                                <i class="fas fa-wind"></i>
                                <span><?= isset($weatherData['wind_speed']) ? round(floatval($weatherData['wind_speed']) * 3.6) . ' km/h' : 'N/A' ?></span>
                                <small>Vent</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer" style="border-top:0; padding-top:0;"> <!-- Footer for form, no border if content above has it -->
                     <form class="weather-city-form" action="/update-weather-city" method="post" style="margin-top: <?php echo (isset($weatherData['error'])) ? '10px' : '0'; ?>;">
                        <div class="input-group">
                            <input type="text" name="city" placeholder="Changer de ville..." class="weather-city-input" 
                                value="<?= htmlspecialchars($_SESSION['weather_city'] ?? (defined('WEATHER_DEFAULT_CITY') ? WEATHER_DEFAULT_CITY : 'Paris')) ?>" required>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                    <?php if ((!defined('WEATHER_API_KEY') || WEATHER_API_KEY === 'YOUR_API_KEY' || WEATHER_API_KEY === '')) : ?>
                        <div style="background-color: #fff3cd; border-left: 4px solid #ffeeba; color: #856404; padding: 10px; margin-top: 15px; border-radius: 5px; font-size: 0.9em;">
                            <i class="fas fa-info-circle"></i> La clé API Météo n'est pas configurée. Modifiez <code>src/config.php</code>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Température Simulé Tile -->
            <div class="card" id="temperature-card">
                <div class="card-content">
                    <h2 class="card-title"><i class="fas fa-thermometer-half"></i> Température Actuelle</h2>
                    <?php if ($latestTemperature): ?>
                        <?php
                            $tempValue = floatval($latestTemperature['valeur']);
                            $tempStatusClass = '';
                            $tempStatusIcon = 'fa-thermometer-half'; // Default
                            if ($tempValue > 37) {
                                $tempStatusClass = 'status-hot'; // Will be styled as green
                                $tempStatusIcon = 'fa-thermometer-full';
                                $tempStatusClass = ($tempValue > 37) ? 'status-hot' : 'status-normal-or-cold';

                            } else {
                                 $tempStatusClass = 'status-normal-or-cold';
                            }
                        ?>
                        <div class="status-indicator">
                            <div class="status-circle <?= $tempValue > 37 ? 'status-on-custom-green' : 'status-off-custom-red' // Custom classes for temp
                            ?>"></div>
                            <span class="status-text">
                                <?= htmlspecialchars(number_format($tempValue, 1)) ?> °C
                            </span>
                        </div>
                        <p style="font-size: 0.9em; color: #555;">
                            Dernière mesure: <?= htmlspecialchars(date("d/m/Y H:i:s", strtotime($latestTemperature['dates']))) ?>
                        </p>
                    <?php else: ?>
                        <div class="status-indicator">
                            <div class="status-circle status-off"></div> <!-- Default off if no data -->
                            <span class="status-text">N/A</span>
                        </div>
                        <p style="font-size: 0.9em; color: #555;">Aucune donnée de température disponible.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div> <!-- End of dashboard-grid -->
    </div>
    
    <script>
        function updateMotorSpeed() {
            const speedSlider = document.getElementById('motor-speed-slider');
            const speedValue = parseInt(speedSlider.value, 10);

            const data = new FormData();
            data.append('motor_speed', speedValue);
            
            fetch('/update-motor-speed', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('current-speed-display').textContent = speedValue;
                    const statusCircle = document.querySelector('.card .status-indicator .status-circle');
                    if (speedValue > 0) {
                        statusCircle.classList.remove('status-off');
                        statusCircle.classList.add('status-on');
                    } else {
                        statusCircle.classList.remove('status-on');
                        statusCircle.classList.add('status-off');
                    }
                } else {
                    alert('Erreur lors de la mise à jour de la vitesse: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Erreur de communication avec le serveur.');
            });
        }
        
        const speedSlider = document.getElementById('motor-speed-slider');
        const sliderSpeedValueDisplay = document.getElementById('slider-speed-value');
        
        if (speedSlider && sliderSpeedValueDisplay) {
            speedSlider.addEventListener('input', function() {
                sliderSpeedValueDisplay.textContent = this.value;
            });
        }
    </script>
</body>
</html>