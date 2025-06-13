<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - ManegePark</title>
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
        .form-group {
            margin-bottom: 25px;
            position: relative;
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
        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s;
            background-color: rgba(255, 255, 255, 0.9);
        }
        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
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
        .btn-danger {
            background: linear-gradient(45deg, #e04055, #dc3545);
            color: white;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .users-table th, .users-table td {
            padding: 15px;
            text-align: left;
        }
        .users-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }
        .users-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .users-table tr:hover {
            background-color: rgba(255, 107, 0, 0.05);
        }
        .users-table td {
            border-bottom: 1px solid #e9ecef;
        }
        .error {
            color: var(--danger-color);
            margin-bottom: 20px;
            padding: 15px;
            background-color: rgba(220, 53, 69, 0.1);
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .error i {
            margin-right: 10px;
            font-size: 18px;
        }
        .success {
            color: var(--success-color);
            margin-bottom: 20px;
            padding: 15px;
            background-color: rgba(40, 167, 69, 0.1);
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .success i {
            margin-right: 10px;
            font-size: 18px;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
        }
        .role-badge i {
            margin-right: 5px;
        }
        .role-admin {
            background-color: var(--accent-color);
            color: var(--dark-color);
        }
        .role-security {
            background-color: var(--secondary-color);
            color: white;
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
            .users-table {
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
                    <span>MP</span>
                </div>
                <h1>Administration des Utilisateurs - ManegePark</h1>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user-circle"></i> Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="/dashboard" class="back-btn"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a>
                <a href="/logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <!-- Créer un nouvel utilisateur -->
        <div class="card">
            <h2 class="card-title"><i class="fas fa-user-plus"></i> Créer un nouvel utilisateur</h2>
            
            <?php if (isset($error) && !empty($error)): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success) && !empty($success)): ?>
                <div class="success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="/admin/create-user">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Entrez le nom d'utilisateur" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Entrez le mot de passe" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select id="role" name="role">
                        <option value="security">Agent de sécurité</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Créer l'utilisateur</button>
            </form>
        </div>
        
        <!-- Liste des utilisateurs -->
        <div class="card">
            <h2 class="card-title"><i class="fas fa-users"></i> Liste des utilisateurs</h2>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Rôle</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <span class="role-badge role-<?= htmlspecialchars($user['role']) ?>">
                                <?php if ($user['role'] === 'admin'): ?>
                                    <i class="fas fa-user-shield"></i> Administrateur
                                <?php else: ?>
                                    <i class="fas fa-user-tie"></i> Agent de sécurité
                                <?php endif; ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin' || $_SESSION['username'] !== 'admin'): ?>
                            <form method="post" action="/admin/delete-user" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if ($user['username'] !== $_SESSION['username']): ?>
                            <form method="post" action="/admin/update-role" style="display: inline; margin-left: 5px;">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <input type="hidden" name="role" value="<?= $user['role'] === 'admin' ? 'security' : 'admin' ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-exchange-alt"></i> Changer en <?= $user['role'] === 'admin' ? 'Agent' : 'Admin' ?>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html> 