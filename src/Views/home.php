<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        body { font-family: sans-serif; display: grid; place-content: center; min-height: 100vh; margin: 0; background-color: #f4f4f4; }
        .container { text-align: center; padding: 2rem; background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .success { color: #28a745; }
        .error { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello World!</h1>
        <p>This is the homepage of your new MVC application.</p>
        <p class="<?php echo $connection_status ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($db_status, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    </div>
</body>
</html>