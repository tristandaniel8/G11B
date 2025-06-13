<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - ManegePark</title>
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
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info span {
            margin-right: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .user-info span i {
            margin-right: 8px;
            font-size: 18px;
        }
        .logout-btn {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        .logout-btn i {
            margin-right: 8px;
        }
        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 25px;
            transition: all 0.3s;
            border-top: 5px solid transparent;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            margin-top: 0;
            color: var(--primary-color);
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            font-size: 20px;
            display: flex;
            align-items: center;
        }
        .card-title i {
            margin-right: 10px;
            font-size: 24px;
        }
        .status-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            background-color: #f8f9fa;
        }
        .status-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 15px;
            position: relative;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .status-circle::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: white;
            border-radius: 50%;
            opacity: 0.5;
        }
        .status-on {
            background-color: var(--success-color);
            animation: pulse 2s infinite;
        }
        .status-off {
            background-color: var(--danger-color);
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        .status-text {
            font-weight: 600;
            font-size: 18px;
        }
        .gauge-container {
            width: 100%;
            height: 24px;
            background-color: #e9ecef;
            border-radius: 50px;
            margin-top: 15px;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .gauge-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
            border-radius: 50px;
            transition: width 0.5s ease;
            position: relative;
        }
        .gauge-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.2) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.2) 75%, transparent 75%, transparent);
            background-size: 20px 20px;
            animation: move 1s linear infinite;
            border-radius: 50px;
        }
        @keyframes move {
            0% { background-position: 0 0; }
            100% { background-position: 20px 0; }
        }
        .control-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
        }
        .btn i {
            margin-right: 8px;
        }
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), #FF9500);
            color: white;
        }
        .btn-success {
            background: linear-gradient(45deg, #20b046, #28a745);
            color: white;
        }
        .btn-danger {
            background: linear-gradient(45deg, #e04055, #dc3545);
            color: white;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .history-table th, .history-table td {
            padding: 15px;
            text-align: left;
        }
        .history-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }
        .history-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .history-table tr:hover {
            background-color: rgba(255, 107, 0, 0.05);
        }
        .history-table td {
            border-bottom: 1px solid #e9ecef;
        }
        .view-all-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            padding: 10px 20px;
            border-radius: 50px;
            background-color: rgba(0, 102, 204, 0.1);
        }
        .view-all-link:hover {
            background-color: rgba(0, 102, 204, 0.2);
            transform: translateY(-2px);
        }
        .view-all-link i {
            margin-left: 8px;
        }
        .slider-container {
            margin-top: 20px;
        }
        .slider {
            width: 100%;
            margin-bottom: 15px;
            -webkit-appearance: none;
            appearance: none;
            height: 8px;
            border-radius: 5px;
            background: #e0e0e0;
            outline: none;
        }
        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
        }
        .slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }
        .slider-value {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 24px;
        }
        .admin-link {
            display: block;
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            color: var(--dark-color);
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .admin-link:hover {
            background-color: #e9ecef;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .admin-link i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        .card-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .dashboard-grid {
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
            .user-info {
                margin-bottom: 15px;
                flex-direction: column;
            }
            .user-info span {
                margin-right: 0;
                margin-bottom: 10px;
            }
            .control-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-title">
                <div class="header-logo">
                    <span>MP</span>
                </div>
                <h1>Tableau de Bord - ManegePark</h1>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user-circle"></i> Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="dashboard-grid">
            <!-- Statut du bouton "Prêt" -->
            <div class="card" style="border-top-color: <?= ($latestData['button_status'] == 'Appuyé') ? '#28a745' : '#dc3545' ?>;">
                <h2 class="card-title"><i class="fas fa-hand-pointer"></i> Bouton "Prêt"</h2>
                <p class="card-description">Le bouton indique si tous les passagers sont correctement installés.</p>
                <div class="status-indicator">
                    <div class="status-circle <?= ($latestData['button_status'] == 'Appuyé') ? 'status-on' : 'status-off' ?>"></div>
                    <span class="status-text"><?= htmlspecialchars($latestData['button_status']) ?></span>
                </div>
            </div>
            
            <!-- Statut du moteur -->
            <div class="card" style="border-top-color: <?= ($latestData['motor_status'] == 1) ? '#28a745' : '#dc3545' ?>;">
                <h2 class="card-title"><i class="fas fa-cogs"></i> Moteur</h2>
                <div class="status-indicator">
                    <div class="status-circle <?= ($latestData['motor_status'] == 1) ? 'status-on' : 'status-off' ?>"></div>
                    <span class="status-text"><?= ($latestData['motor_status'] == 1) ? 'Activé' : 'Désactivé' ?></span>
                </div>
                <div class="control-buttons">
                    <button class="btn btn-success" onclick="updateActuator('motor', 1)"><i class="fas fa-play"></i> Activer</button>
                    <button class="btn btn-danger" onclick="updateActuator('motor', 0)"><i class="fas fa-stop"></i> Désactiver</button>
                </div>
            </div>
            
            <!-- Statut de la LED -->
            <div class="card" style="border-top-color: <?= ($latestData['led_status'] == 1) ? '#28a745' : '#dc3545' ?>;">
                <h2 class="card-title"><i class="fas fa-lightbulb"></i> LED</h2>
                <div class="status-indicator">
                    <div class="status-circle <?= ($latestData['led_status'] == 1) ? 'status-on' : 'status-off' ?>"></div>
                    <span class="status-text"><?= ($latestData['led_status'] == 1) ? 'Allumée' : 'Éteinte' ?></span>
                </div>
                <div class="control-buttons">
                    <button class="btn btn-success" onclick="updateActuator('led', 1)"><i class="fas fa-power-off"></i> Allumer</button>
                    <button class="btn btn-danger" onclick="updateActuator('led', 0)"><i class="fas fa-power-off"></i> Éteindre</button>
                </div>
            </div>
            
            <!-- Valeur du potentiomètre -->
            <div class="card" style="border-top-color: var(--accent-color);">
                <h2 class="card-title"><i class="fas fa-sliders-h"></i> Potentiomètre</h2>
                <p>Valeur actuelle: <span class="slider-value"><?= htmlspecialchars($latestData['potentiometer_value']) ?></span> / 1023</p>
                <div class="gauge-container">
                    <div class="gauge-fill" style="width: <?= ($latestData['potentiometer_value'] / 1023) * 100 ?>%;"></div>
                </div>
                <div class="slider-container">
                    <input type="range" min="0" max="1023" value="<?= htmlspecialchars($latestData['potentiometer_value']) ?>" class="slider" id="potentiometer-slider">
                    <button class="btn btn-primary" onclick="updatePotentiometer()"><i class="fas fa-check"></i> Appliquer</button>
                </div>
            </div>
        </div>
        
        <!-- Historique récent -->
        <div class="card">
            <h2 class="card-title"><i class="fas fa-history"></i> Historique Récent</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date/Heure</th>
                        <th>Bouton</th>
                        <th>Moteur</th>
                        <th>LED</th>
                        <th>Potentiomètre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $entry): ?>
                    <tr>
                        <td><?= htmlspecialchars($entry['timestamp']) ?></td>
                        <td>
                            <div class="status-circle <?= ($entry['button_status'] == 'Appuyé') ? 'status-on' : 'status-off' ?>" style="display: inline-block; vertical-align: middle; margin-right: 8px; width: 12px; height: 12px;"></div>
                            <?= htmlspecialchars($entry['button_status']) ?>
                        </td>
                        <td>
                            <div class="status-circle <?= ($entry['motor_status'] == 1) ? 'status-on' : 'status-off' ?>" style="display: inline-block; vertical-align: middle; margin-right: 8px; width: 12px; height: 12px;"></div>
                            <?= ($entry['motor_status'] == 1) ? 'Activé' : 'Désactivé' ?>
                        </td>
                        <td>
                            <div class="status-circle <?= ($entry['led_status'] == 1) ? 'status-on' : 'status-off' ?>" style="display: inline-block; vertical-align: middle; margin-right: 8px; width: 12px; height: 12px;"></div>
                            <?= ($entry['led_status'] == 1) ? 'Allumée' : 'Éteinte' ?>
                        </td>
                        <td><?= htmlspecialchars($entry['potentiometer_value']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="/history" class="view-all-link">Voir l'historique complet <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="/admin" class="admin-link"><i class="fas fa-users-cog"></i> Administration des utilisateurs</a>
        <?php endif; ?>
    </div>
    
    <script>
        // Fonction pour mettre à jour les actionneurs (moteur, LED)
        function updateActuator(type, value) {
            const data = new FormData();
            
            if (type === 'motor') {
                data.append('motor_status', value);
                data.append('led_status', <?= $latestData['led_status'] ?>);
            } else if (type === 'led') {
                data.append('motor_status', <?= $latestData['motor_status'] ?>);
                data.append('led_status', value);
            }
            
            data.append('potentiometer_value', <?= $latestData['potentiometer_value'] ?>);
            
            fetch('/update-actuators', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recharger la page pour voir les changements
                    location.reload();
                }
            });
        }
        
        // Fonction pour mettre à jour le potentiomètre
        function updatePotentiometer() {
            const sliderValue = document.getElementById('potentiometer-slider').value;
            const data = new FormData();
            
            data.append('motor_status', <?= $latestData['motor_status'] ?>);
            data.append('led_status', <?= $latestData['led_status'] ?>);
            data.append('potentiometer_value', sliderValue);
            
            fetch('/update-actuators', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recharger la page pour voir les changements
                    location.reload();
                }
            });
        }
        
        // Mettre à jour l'affichage de la valeur du potentiomètre en temps réel
        const slider = document.getElementById('potentiometer-slider');
        const sliderValue = document.querySelector('.slider-value');
        const gaugeFill = document.querySelector('.gauge-fill');
        
        slider.addEventListener('input', function() {
            sliderValue.textContent = this.value;
            gaugeFill.style.width = (this.value / 1023) * 100 + '%';
        });
        
        // Rafraîchir automatiquement la page toutes les 10 secondes
        setTimeout(() => {
            location.reload();
        }, 10000);
    </script>
</body>
</html> 