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
            --success-color: #28a745;
            --danger-color: #dc3545;
            --dark-color: #333;
            --bg-color: #f8f9fa;
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
        
        .btn {
            padding: 10px 18px; border: none; border-radius: 50px; cursor: pointer;
            font-weight: 600; display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.3s; box-shadow: 0 3px 10px rgba(0,0,0,0.1); text-decoration: none;
        }
        .btn i { margin-right: 8px; }
        .btn-primary { background: linear-gradient(45deg, var(--primary-color), #FF9500); color: white; }
        .btn-secondary { background: linear-gradient(45deg, #6c757d, #495057); color: white; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        
        .form-group { margin-bottom: 20px; }
        .form-check {
            display: flex; align-items: center; margin-bottom: 10px; padding: 8px;
            border-radius: 8px; transition: background-color 0.2s;
        }
        .form-check:hover { background-color: rgba(0,0,0,0.02); }
        .form-check input[type="checkbox"] { width: 18px; height: 18px; margin-right: 12px; cursor: pointer; }
        .form-check label { cursor: pointer; font-weight: 500; display: flex; align-items: center; }
        .event-icon {
            margin-right: 8px; width: 28px; height: 28px; display: flex; align-items: center;
            justify-content: center; border-radius: 50%; color: white; font-size: 13px;
        }
        .event-icon.motor { background-color: var(--primary-color); }
        .event-icon.system { background-color: var(--danger-color); }
        .event-icon.test { background-color: var(--secondary-color); }

        .message { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: 500; }
        .alert-success { background-color: rgba(40,167,69,0.1); color: var(--success-color); }
        .alert-danger { background-color: rgba(220,53,69,0.1); color: var(--danger-color); }

        .email-logs-table-container { overflow-x: auto; }
        .email-logs-table { width: 100%; border-collapse: collapse; min-width: 700px; }
        .email-logs-table th, .email-logs-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        .email-logs-table th { background-color: #f8f9fa; font-weight: 600; color: var(--primary-color); }
        .email-logs-table tr:hover { background-color: #f1f1f1; }
        .status-badge {
            padding: 4px 8px; border-radius: 20px; font-size: 0.75em; font-weight: bold;
            display: inline-flex; align-items: center;
        }
        .status-badge i { margin-right: 4px;}
        .status-success { background-color: rgba(40,167,69,0.1); color: var(--success-color); }
        .status-failure { background-color: rgba(220,53,69,0.1); color: var(--danger-color); }
        .empty-state { text-align: center; padding: 30px 20px; color: #666; }
        .empty-state i { font-size: 40px; margin-bottom: 15px; color: #ccc; }

        @media (max-width: 768px) {
            .header-content { flex-direction: column; text-align: center; }
            .user-info { margin-top: 15px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-title">
                <div class="header-logo"><i class="fas fa-envelope-open-text"></i></div>
                <h1>Notifications par Email</h1>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user"></i> <?= htmlspecialchars($username ?? 'Utilisateur') ?></span>
                <a href="/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a>
                <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php if (!empty($successMessage)): ?>
            <div class="message alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="message alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-cogs"></i> Paramètres de Notification</h2>
            <p>Choisissez les événements pour lesquels vous souhaitez être notifié par email.</p>
            
            <form method="post" action="/email-notifications">
                <div class="form-group">
                    <h3>Événements du Moteur</h3>
                    <div class="form-check">
                        <input type="checkbox" id="motor_speed_changed" name="motor_speed_changed" <?= isset($settings['motor_speed_changed']) && $settings['motor_speed_changed'] ? 'checked' : '' ?>>
                        <label for="motor_speed_changed">
                            <span class="event-icon motor"><i class="fas fa-tachometer-alt"></i></span>
                            Changement de vitesse du moteur
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <h3>Alertes Générales</h3>
                    <div class="form-check">
                        <input type="checkbox" id="system_alert" name="system_alert" <?= isset($settings['system_alert']) && $settings['system_alert'] ? 'checked' : '' ?>>
                        <label for="system_alert">
                            <span class="event-icon system"><i class="fas fa-exclamation-triangle"></i></span>
                            Alertes système critiques
                        </label>
                    </div>
                </div>
                
                <button type="submit" name="update_settings" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="submit" name="send_test" class="btn btn-secondary" style="margin-left: 10px;">
                    <i class="fas fa-paper-plane"></i> Email de Test
                </button>
            </form>
        </div>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-history"></i> Historique des Notifications Envoyées</h2>
            <?php if (empty($emailLogs)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Aucune notification envoyée</h3>
                    <p>L'historique s'affichera ici.</p>
                </div>
            <?php else: ?>
                <div class="email-logs-table-container">
                    <table class="email-logs-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <?php if ($isAdmin): ?><th>Utilisateur</th><?php endif; ?>
                                <th>Destinataire</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($emailLogs as $log): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date("d/m/Y H:i", strtotime($log['sent_at']))) ?></td>
                                    <td>
                                        <?php
                                        $iconClass = 'fas fa-info-circle'; // Default
                                        $iconStyle = 'background-color: var(--dark-color);';
                                        if ($log['event_type'] === 'motor_speed_changed') {
                                            $iconClass = 'fas fa-tachometer-alt'; $iconStyle = 'background-color: var(--primary-color);';
                                        } elseif ($log['event_type'] === 'system_alert') {
                                            $iconClass = 'fas fa-exclamation-triangle'; $iconStyle = 'background-color: var(--danger-color);';
                                        } elseif ($log['event_type'] === 'test') {
                                            $iconClass = 'fas fa-vial'; $iconStyle = 'background-color: var(--secondary-color);';
                                        } elseif ($log['event_type'] === 'password_reset') {
                                            $iconClass = 'fas fa-key'; $iconStyle = 'background-color: var(--accent-color); color: var(--dark-color);';
                                        }
                                        echo '<span class="event-icon" style="'.$iconStyle.'"><i class="'.$iconClass.'"></i></span> ';
                                        echo htmlspecialchars(ucfirst(str_replace('_', ' ', $log['event_type'])));
                                        ?>
                                    </td>
                                    <?php if ($isAdmin): ?><td><?= htmlspecialchars($log['username'] ?? 'N/A') ?></td><?php endif; ?>
                                    <td><?= htmlspecialchars($log['email_to']) ?></td>
                                    <td><?= htmlspecialchars($log['subject']) ?></td>
                                    <td>
                                        <?php if ($log['status'] === 'success'): ?>
                                            <span class="status-badge status-success"><i class="fas fa-check-circle"></i> Envoyé</span>
                                        <?php else: ?>
                                            <span class="status-badge status-failure" title="<?= htmlspecialchars($log['error_message'] ?? 'Aucun détail') ?>"><i class="fas fa-times-circle"></i> Échec</span>
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