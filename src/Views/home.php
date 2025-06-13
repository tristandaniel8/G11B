<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Système de Gestion Manège</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 10%;
        }
        h1 {
            font-size: 36px;
        }
        h2 {
            font-size: 24px;
            color: #ff6600;
        }
        .btn {
            background-color: teal;
            color: white;
            padding: 12px 20px;
            border: none;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }
        .status {
            margin-top: 40px;
            font-style: italic;
            color: gray;
        }
    </style>
</head>
<body>
    <h1>Système de Gestion Manège</h1>
    <h2>Le Grand Carrousel</h2>

    <a href="/login"><button class="btn">Connexion</button></a>

    <div class="status">
        <?php if (isset($db_status)) echo htmlspecialchars($db_status); ?>
    </div>
</body>
</html>
