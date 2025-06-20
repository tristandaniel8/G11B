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
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px;
            box-sizing: border-box; font-size: 15px; transition: all 0.3s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus {
            border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 3px rgba(255,107,0,0.2);
        }
        .btn {
            padding: 10px 18px; border: none; border-radius: 50px; cursor: pointer;
            font-weight: 600; display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.3s; box-shadow: 0 3px 10px rgba(0,0,0,0.1); text-decoration: none;
        }
        .btn i { margin-right: 8px; }
        .btn-primary { background: linear-gradient(45deg, var(--primary-color), #FF9500); color: white; }
        .btn-danger { background: linear-gradient(45deg, #e04055, #dc3545); color: white; }
        .btn-secondary { background: linear-gradient(45deg, var(--secondary-color), #0056b3); color: white;}
        .btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

        .users-table-container { overflow-x: auto; }
        .users-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .users-table th, .users-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        .users-table th { background-color: #f8f9fa; font-weight: 600; color: var(--primary-color); }
        .users-table tr:hover { background-color: #f1f1f1; }
        
        .message { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: 500; }
        .error { background-color: rgba(220,53,69,0.1); color: var(--danger-color); }
        .success { background-color: rgba(40,167,69,0.1); color: var(--success-color); }
        .role-badge {
            padding: 5px 10px; border-radius: 20px; font-size: 0.8em; color: white;
            display: inline-flex; align-items: center;
        }
        .role-badge i { margin-right: 5px; }
        .role-admin { background-color: var(--accent-color); color: var(--dark-color); }
        .role-security { background-color: var(--secondary-color); }

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
                <div class="header-logo"><i class="fas fa-user-shield"></i></div>
                <h1>Administration des Utilisateurs</h1>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                <a href="/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a>
                <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php if (!empty($error)): ?>
            <div class="message error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="message success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-user-plus"></i> Créer un Nouvel Utilisateur</h2>
            <form method="post" action="/admin/create-user">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="adresse@exemple.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Mot de passe (min. 6 caractères)" required>
                </div>
                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select id="role" name="role">
                        <option value="security">Agent de Sécurité</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Créer</button>
            </form>
        </div>
        
        <div class="card">
            <h2 class="card-title"><i class="fas fa-users"></i> Liste des Utilisateurs</h2>
            <div class="users-table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom d'utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                            <td>
                                <span class="role-badge role-<?= htmlspecialchars($user['role']) ?>">
                                    <i class="fas fa-<?= $user['role'] === 'admin' ? 'user-shield' : 'user-tie' ?>"></i>
                                    <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars(date("d/m/Y H:i", strtotime($user['created_at']))) ?></td>
                            <td>
                                <?php if ($user['id'] !== $_SESSION['user_id'] && !($user['role'] === 'admin' && $user['username'] === 'admin')): ?>
                                    <form method="post" action="/admin/delete-user" style="display: inline-block; margin-right: 5px;">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <form method="post" action="/admin/update-role" style="display: inline-block;">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="role" value="<?= $user['role'] === 'admin' ? 'security' : 'admin' ?>">
                                        <button type="submit" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-exchange-alt"></i> <?= $user['role'] === 'admin' ? 'Agent' : 'Admin' ?>
                                        </button>
                                    </form>
                                <?php else: echo "N/A"; endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>