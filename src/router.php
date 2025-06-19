<?php

// Inclure les contrôleurs
require_once __DIR__ . '/Controllers/HomeController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/DashboardController.php';
require_once __DIR__ . '/Controllers/AdminController.php';
require_once __DIR__ . '/Controllers/EmailNotificationController.php';
require_once __DIR__ . '/Controllers/EcoResponsibilityController.php';
require_once __DIR__ . '/Controllers/ContactController.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupérer l'URI de la requête
$request_uri = strtok($_SERVER['REQUEST_URI'], '?');

// Router les requêtes vers les contrôleurs appropriés
switch ($request_uri) {
    // Page d'accueil
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;
    
    // Authentification
    case '/login':
        $controller = new AuthController();
        $controller->login();
        break;
        
    case '/register':
        $controller = new AuthController();
        $controller->register();
        break;
        
    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    
    // Tableau de bord et fonctionnalités
    case '/dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case '/history':
        $controller = new DashboardController();
        $controller->history();
        break;
        
    case '/update-actuators':
        $controller = new DashboardController();
        $controller->updateActuators();
        break;
        
    case '/update-weather-city':
        $controller = new DashboardController();
        $controller->updateWeatherCity();
        break;
    
    // API pour récupérer l'historique du moteur
    case '/api/motor-speed-history':
        $controller = new DashboardController();
        $controller->getMotorSpeedHistoryJson();
        break;
    
    // Page d'écoresponsabilité
    case '/eco-responsibility':
        $controller = new EcoResponsibilityController();
        $controller->index();
        break;
    
    // Page de contact
    case '/contact':
        $controller = new ContactController();
        $controller->index();
        break;
        
    case '/contact/submit':
        $controller = new ContactController();
        $controller->submit();
        break;
    
    // Administration
    case '/admin':
        $controller = new AdminController();
        $controller->index();
        break;
        
    case '/admin/create-user':
        $controller = new AdminController();
        $controller->createUser();
        break;
        
    case '/admin/delete-user':
        $controller = new AdminController();
        $controller->deleteUser();
        break;
        
    case '/admin/update-role':
        $controller = new AdminController();
        $controller->updateUserRole();
        break;
        
    // Notifications par email
    case '/email-notifications':
        $controller = new EmailNotificationController();
        $controller->index();
        break;
        
    // Débogage des emails
    case '/debug-email':
        require_once __DIR__ . '/debug_email.php';
        break;
    
    // Page non trouvée
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}