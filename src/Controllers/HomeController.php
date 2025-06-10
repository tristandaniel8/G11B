<?php

require_once __DIR__ . '/../Models/Database.php';

class HomeController {
    public function index() {
        $db_host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_pass = getenv('DB_PASS');
        
        $db = new Database($db_host, $db_name, $db_user, $db_pass);
        $connection_status = false;
        $db_status = '';

        try {
            $db->getConnection();
            $connection_status = true;
            $db_status = "Database connected successfully!";
        } catch (PDOException $e) {
            $db_status = "Database connection failed: " . $e->getMessage();
        }

        require_once __DIR__ . '/../Views/home.php';
    }
}