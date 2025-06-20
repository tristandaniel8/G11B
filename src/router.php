<?php

// Inclure les contrôleurs
require_once __DIR__ . '/Controllers/HomeController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/DashboardController.php';
require_once __DIR__ . '/Controllers/AdminController.php';
require_once __DIR__ . '/Controllers/EmailNotificationController.php';
require_once __DIR__ . '/Controllers/EcoResponsibilityController.php';
require_once __DIR__ . '/Controllers/ContactController.php';

$request_uri = strtok($_SERVER['REQUEST_URI'], '?');

switch ($request_uri) {
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;
    
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
    case '/forgot-password':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handleForgotPassword();
        } else {
            $controller->showForgotPasswordForm();
        }
        break;
    case '/reset-password':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handleResetPassword();
        } else {
            $controller->showResetPasswordForm();
        }
        break;
    
    case '/dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
    case '/history': // For motor speed history
        $controller = new DashboardController();
        $controller->history();
        break;
    case '/update-motor-speed': // Renamed from /update-actuators
        $controller = new DashboardController();
        $controller->updateMotorSpeed();
        break;
    case '/update-weather-city':
        $controller = new DashboardController();
        $controller->updateWeatherCity();
        break;
    case '/api/motor-speed-history': // For AJAX graph updates on history page
        $controller = new DashboardController();
        $controller->getMotorSpeedHistoryJson();
        break;
    
    case '/eco-responsibility':
        $controller = new EcoResponsibilityController();
        $controller->index();
        break;
    case '/contact':
        $controller = new ContactController();
        $controller->index();
        break;
    case '/contact/submit':
        $controller = new ContactController();
        $controller->submit();
        break;
    
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
        
    case '/email-notifications':
        $controller = new EmailNotificationController();
        $controller->index();
        break;
    case '/debug-email': // Keep for MailHog debugging if needed
        require_once __DIR__ . '/debug_email.php';
        break;
    
    default:
        http_response_code(404);
        // You could create a Views/404.php and include it here
        echo "404 Not Found - Page '" . htmlspecialchars($request_uri) . "' non trouvée.";
        break;
}