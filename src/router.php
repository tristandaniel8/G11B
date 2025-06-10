<?php

require_once __DIR__ . '/Controllers/HomeController.php';

$request_uri = strtok($_SERVER['REQUEST_URI'], '?');

switch ($request_uri) {
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}