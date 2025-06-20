<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique Vitesse Moteur - ManegePark</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #FF6B00;
            --secondary-color: #0066CC;
            --dark-color: #333;
            --bg-color: #f8f9fa;
            --gray-color: #6c757d;
            --success-color: #28a745;
        }
        body {
            font-family: 'Montserrat', Arial, sans-serif; margin: 0; padding: 0;
            background-color: var(--bg-color); color: var(--dark-color);
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white; padding: 20px 0; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header-content {
            display: flex; justify-content: space-between; align-items: center;
            max-width: 1200px; margin: 0 auto; padding: 0 20px;
        }
        .header-title { display: flex; align-items: center; }
        .header-logo {
            width: 50px; height: 50px; background-color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 24px;
            color: var(--primary-color); font-weight: bold; margin-right: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        .header-logo i { font-size: 28px; }
        .user-info { display: flex; align-items: center; }
        .user-info span { margin-right: 15px; font-weight: 500; }
        .nav-link {
            background-color: rgba(255,255,255,0.2); border: none; color: white; padding: 10px 15px;
            border-radius: 50px; cursor: pointer; text-decoration: none; margin-left: 10px;
            font-weight: 600; display: flex; align-items: center; transition: all 0.3s;
        }
        .nav-link i { margin-right: 8px; }
        .nav-link:hover { background-color: rgba(255,255,255,0.3); transform: translateY(-2px); }
        
        .card {
            background-color: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 25px; margin-bottom: 30px; border-top: 5px solid var(--primary-color);
        }
        .card-title {
            margin-top: 0; color: var(--primary-color); border-bottom: 1px solid #eee;
            padding-bottom: 15px; font-size: 20px; display: flex; align-items: center;
        }
        .card-title i { margin-right: 10px; font-size: 24px; }

        .filter-form {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px; margin-bottom: 20px; align-items: end;
        }
        .form-group { margin-bottom: 0; } /* Adjusted for grid alignment */
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; }
        input[type="date"], select {
            width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px;
            box-sizing: border-box; font-size: 15px; transition: all 0.3s;
        }
        input[type="date"]:focus, select:focus {
            border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 3px rgba(255,107,0,0.2);
        }
        .btn {
            padding: 12px 20px; border: none; border-radius: 50px; cursor: pointer;
            font-weight: 600; display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.3s; box-shadow: 0 3px 10px rgba(0,0,0,0.1); text-decoration: none;
        }
        .btn i { margin-right: 8px; }
        .btn-primary { background: linear-gradient(45deg, var(--primary-color), #FF9500); color: white; }
        .btn-secondary { background: linear-gradient(45deg, var(--gray-color), #5a6268); color: white; }
        .btn-export { background: linear-gradient(45deg, var(--success-color), #218838); color: white; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

        .history-table-container { overflow-x: auto; }
        .history-table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .history-table th, .history-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        .history-table th { background-color: #f8f9fa; font-weight: 600; color: var(--primary-color); }
        .history-table tr:hover { background-color: #f1f1f1; }
        .chart-container { height: 400px; margin-bottom: 30px; position: relative; }
        
        @media (max-width: 768px) {
            .filter-form { grid-template-columns: 1fr; }
            .header-content { flex-direction: column; text-align: center; }
            .user-info { margin-top: 15px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-title">
                <div class="header-logo"><i class="fas fa-history"></i></div>
                <h1>Historique Vitesse Moteur</h1>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['username'] ?? 'Visiteur') ?></span>
                <a href="/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a>
                <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="card">
            <h2 class="card-title"><i class="fas fa-filter"></i> Filtres</h2>
            <form method="get" action="/history" class="filter-form">
                <div class="form-group">
                    <label for="start_date">Date de début</label>
                    <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="end_date">Date de fin</label>
                    <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
                </div>
                <div class="form-group" style="display:flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex-grow:1;"><i class="fas fa-search"></i> Filtrer</button>
                    <a href="/history" class="btn btn-secondary" style="flex-grow:1;"><i class="fas fa-undo"></i> Réinitialiser</a>
                </div>
            </form>
             <a href="/history?<?= http_build_query(array_merge($_GET, ['export' => 'csv'])) ?>" class="btn btn-export" style="margin-top: 15px;"><i class="fas fa-file-csv"></i> Exporter en CSV</a>
        </div>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-chart-line"></i> Graphique d'Évolution des Vitesses</h2>
            <div class="chart-container">
                <canvas id="motorSpeedHistoryChart"></canvas>
            </div>
        </div>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-list-alt"></i> Données Détaillées des Mises à Jour</h2>
            <div class="history-table-container">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>ID Mise à Jour</th>
                            <th>Date/Heure</th>
                            <th>Nouvelle Vitesse (0-10)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($motorSpeedHistory)): ?>
                            <tr><td colspan="3" style="text-align:center; padding: 20px;">Aucune donnée d'historique trouvée pour les filtres sélectionnés.</td></tr>
                        <?php else: ?>
                            <?php foreach (array_reverse($motorSpeedHistory) as $entry): // Display most recent first in table ?>
                            <tr>
                                <td><?= htmlspecialchars($entry['updateId']) ?></td>
                                <td><?= htmlspecialchars(date("d/m/Y H:i:s", strtotime($entry['updateTime']))) ?></td>
                                <td><?= htmlspecialchars($entry['newSpeed']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        const historyData = <?= json_encode($motorSpeedHistory) ?>;
        const labels = historyData.map(entry => new Date(entry.updateTime));
        const speeds = historyData.map(entry => entry.newSpeed);

        const ctx = document.getElementById('motorSpeedHistoryChart').getContext('2d');
        const motorSpeedChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Vitesse du Moteur (0-10)',
                    data: speeds,
                    borderColor: 'var(--primary-color)',
                    backgroundColor: 'rgba(255, 107, 0, 0.2)',
                    borderWidth: 2,
                    tension: 0.1,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: 'var(--primary-color)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'hour', // Adjust unit based on data span (day, month)
                             tooltipFormat: 'dd/MM/yyyy HH:mm:ss',
                            displayFormats: {
                                hour: 'HH:mm'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Date et Heure'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 10,
                        min:0,
                        ticks: { stepSize: 1 },
                        title: {
                            display: true,
                            text: 'Vitesse (0-10)'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function(tooltipItems) {
                                // Format date in tooltip
                                return new Date(tooltipItems[0].parsed.x).toLocaleString('fr-FR', { dateStyle: 'short', timeStyle: 'medium' });
                            }
                        }
                    },
                    legend: {
                         display: false // Or true if you want to show it
                    }
                }
            }
        });
    </script>
</body>
</html>