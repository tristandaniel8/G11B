<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications Email - ManegePark</title>
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
            background: linear-gradient(45deg, #6c757d, #495057);
            color: white;
        }
        .btn-success {
            background: linear-gradient(45deg, #20b046, #28a745);
            color: white;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }
        .form-check:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .form-check input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            cursor: pointer;
        }
        .form-check label {
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        .form-check label .event-icon {
            margin-right: 10px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 14px;
        }
        .event-icon.button {
            background-color: var(--secondary-color);
        }
        .event-icon.motor {
            background-color: var(--primary-color);
        }
        .event-icon.led {
            background-color: var(--warning-color);
        }
        .event-icon.potentiometer {
            background-color: var(--success-color);
        }
        .event-icon.system {
            background-color: var(--danger-color);
        }
        .notification-description {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .alert i {
            margin-right: 10px;
            font-size: 18px;
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        .email-logs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .email-logs-table th, .email-logs-table td {
            padding: 15px;
            text-align: left;
        }
        .email-logs-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }
        .email-logs-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .email-logs-table tr:hover {
            background-color: rgba(255, 107, 0, 0.05);
        }
        .email-logs-table td {
            border-bottom: 1px solid #e9ecef;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }
        .status-error {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ccc;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
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
            .email-logs-table {
                display: block;
                overflow-x: auto;
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
                <h1>Notifications Email - ManegePark</h1>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user"></i> Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="/dashboard" class="back-btn"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a>
                <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <!-- Configuration des notifications -->
        <div class="card">
            <h2 class="card-title"><i class="fas fa-bell"></i> Paramètres de notification</h2>
            <p>Configurez les types d'événements pour lesquels vous souhaitez recevoir des notifications par email.</p>
            
            <form method="post" action="/email-notifications">
                <div class="form-group">
                    <h3>Événements du bouton "Prêt"</h3>
                    <div class="form-check">
                        <input type="checkbox" id="button_pressed" name="button_pressed" <?= isset($settings['button_pressed']) && $settings['button_pressed'] ? 'checked' : '' ?>>
                        <label for="button_pressed">
                            <div>
                                <span class="event-icon button"><i class="fas fa-hand-pointer"></i></span>
                                Bouton appuyé
                            </div>
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="button_released" name="button_released" <?= isset($settings['button_released']) && $settings['button_released'] ? 'checked' : '' ?>>
                        <label for="button_released">
                            <div>
                                <span class="event-icon button"><i class="fas fa-hand-paper"></i></span>
                                Bouton relâché
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <h3>Événements du moteur</h3>
                    <div class="form-check">
                        <input type="checkbox" id="motor_on" name="motor_on" <?= isset($settings['motor_on']) && $settings['motor_on'] ? 'checked' : '' ?>>
                        <label for="motor_on">
                            <div>
                                <span class="event-icon motor"><i class="fas fa-play"></i></span>
                                Moteur activé
                            </div>
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="motor_off" name="motor_off" <?= isset($settings['motor_off']) && $settings['motor_off'] ? 'checked' : '' ?>>
                        <label for="motor_off">
                            <div>
                                <span class="event-icon motor"><i class="fas fa-stop"></i></span>
                                Moteur désactivé
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <h3>Événements de la LED</h3>
                    <div class="form-check">
                        <input type="checkbox" id="led_on" name="led_on" <?= isset($settings['led_on']) && $settings['led_on'] ? 'checked' : '' ?>>
                        <label for="led_on">
                            <div>
                                <span class="event-icon led"><i class="fas fa-lightbulb"></i></span>
                                LED allumée
                            </div>
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="led_off" name="led_off" <?= isset($settings['led_off']) && $settings['led_off'] ? 'checked' : '' ?>>
                        <label for="led_off">
                            <div>
                                <span class="event-icon led"><i class="far fa-lightbulb"></i></span>
                                LED éteinte
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <h3>Autres événements</h3>
                    <div class="form-check">
                        <input type="checkbox" id="potentiometer_changed" name="potentiometer_changed" <?= isset($settings['potentiometer_changed']) && $settings['potentiometer_changed'] ? 'checked' : '' ?>>
                        <label for="potentiometer_changed">
                            <div>
                                <span class="event-icon potentiometer"><i class="fas fa-sliders-h"></i></span>
                                Changement de potentiomètre
                            </div>
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="system_alert" name="system_alert" <?= isset($settings['system_alert']) && $settings['system_alert'] ? 'checked' : '' ?>>
                        <label for="system_alert">
                            <div>
                                <span class="event-icon system"><i class="fas fa-exclamation-triangle"></i></span>
                                Alertes système
                            </div>
                        </label>
                    </div>
                </div>
                
                <button type="submit" name="update_settings" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les préférences
                </button>
                
                <button type="submit" name="send_test" class="btn btn-secondary" style="margin-left: 10px;">
                    <i class="fas fa-paper-plane"></i> Envoyer un email de test
                </button>
            </form>
        </div>
        
        <!-- Historique des emails -->
        <div class="card">
            <h2 class="card-title"><i class="fas fa-history"></i> Historique des notifications</h2>
            
            <?php if (empty($emailLogs)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Aucune notification envoyée</h3>
                    <p>L'historique des notifications par email s'affichera ici une fois que des emails auront été envoyés.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="email-logs-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <?php if ($isAdmin): ?>
                                    <th>Utilisateur</th>
                                <?php endif; ?>
                                <th>Destinataire</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($emailLogs as $log): ?>
                                <tr>
                                    <td><?= htmlspecialchars($log['sent_at']) ?></td>
                                    <td>
                                        <?php
                                        $eventIcons = [
                                            'button_pressed' => '<span class="event-icon button"><i class="fas fa-hand-pointer"></i></span>',
                                            'button_released' => '<span class="event-icon button"><i class="fas fa-hand-paper"></i></span>',
                                            'motor_on' => '<span class="event-icon motor"><i class="fas fa-play"></i></span>',
                                            'motor_off' => '<span class="event-icon motor"><i class="fas fa-stop"></i></span>',
                                            'led_on' => '<span class="event-icon led"><i class="fas fa-lightbulb"></i></span>',
                                            'led_off' => '<span class="event-icon led"><i class="far fa-lightbulb"></i></span>',
                                            'potentiometer_changed' => '<span class="event-icon potentiometer"><i class="fas fa-sliders-h"></i></span>',
                                            'system_alert' => '<span class="event-icon system"><i class="fas fa-exclamation-triangle"></i></span>',
                                            'test' => '<span class="event-icon button"><i class="fas fa-vial"></i></span>'
                                        ];
                                        echo isset($eventIcons[$log['event_type']]) ? $eventIcons[$log['event_type']] . ' ' : '';
                                        echo htmlspecialchars($log['event_type']);
                                        ?>
                                    </td>
                                    <?php if ($isAdmin): ?>
                                        <td><?= htmlspecialchars($log['username']) ?></td>
                                    <?php endif; ?>
                                    <td><?= htmlspecialchars($log['email_to']) ?></td>
                                    <td><?= htmlspecialchars($log['subject']) ?></td>
                                    <td>
                                        <?php if (strpos($log['status'], 'succès') !== false): ?>
                                            <span class="status-badge status-success">
                                                <i class="fas fa-check-circle"></i> Envoyé
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-error">
                                                <i class="fas fa-times-circle"></i> Échec
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 