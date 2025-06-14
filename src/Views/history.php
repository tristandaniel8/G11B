<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - ManegePark</title>
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
            --gray-color: #6c757d;
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
        .logout-btn, .back-btn {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            margin-left: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        .logout-btn i, .back-btn i {
            margin-right: 8px;
        }
        .logout-btn:hover, .back-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
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
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
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
        input[type="date"], select {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s;
            background-color: rgba(255, 255, 255, 0.9);
        }
        input[type="date"]:focus, select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.2);
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        .btn i {
            margin-right: 8px;
        }
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), #FF9500);
            color: white;
        }
        .btn-secondary {
            background: linear-gradient(45deg, var(--gray-color), #5a6268);
            color: white;
        }
        .btn-export {
            background: linear-gradient(45deg, var(--success-color), #218838);
            color: white;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
        }
        .history-table th, .history-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        .history-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--primary-color);
        }
        .history-table tr:hover {
            background-color: #f8f9fa;
        }
        .chart-container {
            height: 400px;
            margin-bottom: 30px;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .filter-form {
                grid-template-columns: 1fr;
            }
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            .user-info {
                margin-top: 15px;
            }
            .history-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
    <!-- Inclure Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-title">
                <div class="header-logo">
                    <i class="fas fa-history"></i>
                </div>
                <h1>Historique des Données</h1>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="/dashboard" class="back-btn"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a>
                <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <!-- Filtres -->
        <div class="card">
            <h2 class="card-title"><i class="fas fa-filter"></i> Filtres</h2>
            <form method="get" action="/history" class="filter-form">
                <div class="form-group">
                    <label for="start_date">Date de début</label>
                    <input type="date" id="start_date" name="start_date" value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="end_date">Date de fin</label>
                    <input type="date" id="end_date" name="end_date" value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="type">Type d'actionneur</label>
                    <select id="type" name="type">
                        <option value="">Tous</option>
                        <option value="button" <?= (isset($_GET['type']) && $_GET['type'] === 'button') ? 'selected' : '' ?>>Bouton "Prêt"</option>
                        <option value="motor" <?= (isset($_GET['type']) && $_GET['type'] === 'motor') ? 'selected' : '' ?>>Moteur</option>
                        <option value="led" <?= (isset($_GET['type']) && $_GET['type'] === 'led') ? 'selected' : '' ?>>LED</option>
                    </select>
                </div>
                
                <div class="form-group" style="align-self: end;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrer</button>
                    <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Réinitialiser</button>
                </div>
            </form>
            
            <a href="<?= $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') ?>export=csv" class="btn btn-export"><i class="fas fa-file-csv"></i> Exporter en CSV</a>
        </div>
        
        <!-- Graphiques -->
        <div class="card">
            <h2 class="card-title">Graphiques</h2>
            <div class="chart-container">
                <canvas id="historyChart"></canvas>
            </div>
        </div>
        
        <!-- Tableau des données -->
        <div class="card">
            <h2 class="card-title">Données détaillées</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
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
                        <td><?= htmlspecialchars($entry['id']) ?></td>
                        <td><?= htmlspecialchars($entry['timestamp']) ?></td>
                        <td><?= htmlspecialchars($entry['button_status']) ?></td>
                        <td><?= ($entry['motor_status'] == 1) ? 'Activé' : 'Désactivé' ?></td>
                        <td><?= ($entry['led_status'] == 1) ? 'Allumée' : 'Éteinte' ?></td>
                        <td><?= htmlspecialchars($entry['potentiometer_value']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Préparer les données pour le graphique
        const timestamps = <?= json_encode(array_column(array_reverse($history), 'timestamp')) ?>;
        const buttonValues = <?= json_encode(array_map(function($entry) { 
            return $entry['button_status'] === 'Appuyé' ? 1 : 0; 
        }, array_reverse($history))) ?>;
        const potentiometerValues = <?= json_encode(array_map(function($entry) { 
            return $entry['potentiometer_value']; 
        }, array_reverse($history))) ?>;
        
        // Créer le graphique
        const ctx = document.getElementById('historyChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [
                    {
                        label: 'Bouton "Prêt"',
                        data: buttonValues,
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 2,
                        stepped: true,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Potentiomètre',
                        data: potentiometerValues,
                        backgroundColor: 'rgba(0, 128, 128, 0.2)',
                        borderColor: 'rgba(0, 128, 128, 1)',
                        borderWidth: 2,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date/Heure'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Potentiomètre'
                        },
                        min: 0,
                        max: 1023
                    },
                    y1: {
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Bouton'
                        },
                        min: 0,
                        max: 1,
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html> 