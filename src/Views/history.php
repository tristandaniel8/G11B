<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Système de Gestion Manège</title>
    <style>
        :root {
            --primary-color: teal;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --gray-color: #6c757d;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 0;
            margin-bottom: 20px;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info span {
            margin-right: 15px;
        }
        .logout-btn, .back-btn {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-left: 10px;
        }
        .logout-btn:hover, .back-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card-title {
            margin-top: 0;
            color: var(--primary-color);
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
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
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="date"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-secondary {
            background-color: var(--gray-color);
            color: white;
        }
        .btn-export {
            background-color: var(--success-color);
            color: white;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
        }
        .history-table th, .history-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        .history-table th {
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
                margin-bottom: 10px;
                flex-direction: column;
            }
            .user-info span {
                margin-right: 0;
                margin-bottom: 10px;
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
            <h1>Historique des Données</h1>
            <div class="user-info">
                <span>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="/dashboard" class="back-btn">Tableau de Bord</a>
                <a href="/logout" class="logout-btn">Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <!-- Filtres -->
        <div class="card">
            <h2 class="card-title">Filtres</h2>
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
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                </div>
            </form>
            
            <a href="<?= $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') ?>export=csv" class="btn btn-export">Exporter en CSV</a>
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