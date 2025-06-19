<?php
/**
 * API pour contrôler la LED
 */

// Inclure les fichiers nécessaires
require_once '../config.php';
require_once '../Models/Database.php';
require_once '../Models/SensorModel.php';

// Configurer les en-têtes pour permettre l'accès AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gérer les requêtes OPTIONS (pour CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Vérifier que la méthode est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

try {
    // Récupérer les données JSON de la requête
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Vérifier que les données nécessaires sont présentes
    if (!isset($data['state'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Paramètre "state" manquant']);
        exit;
    }
    
    // Convertir l'état en booléen
    $state = (bool)$data['state'];
    
    // Connexion à la base de données
    $db_host = getenv('DB_HOST');
    $db_name = getenv('DB_NAME');
    $db_user = getenv('DB_USER');
    $db_pass = getenv('DB_PASS');
    
    $db = new Database($db_host, $db_name, $db_user, $db_pass);
    
    // Créer une instance du modèle
    $sensorModel = new SensorModel($db->getConnection());
    
    // Récupérer les dernières données pour conserver les autres valeurs
    $latestData = $sensorModel->getLatestData();
    
    // Mettre à jour l'état de la LED
    $result = $sensorModel->updateActuators(
        $latestData['motor_status'],
        $state ? 1 : 0,
        $latestData['potentiometer_value']
    );
    
    // Vérifier si la mise à jour a réussi
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'État de la LED mis à jour avec succès',
            'state' => $state
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Erreur lors de la mise à jour de l\'état de la LED'
        ]);
    }
    
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la mise à jour de l\'état de la LED: ' . $e->getMessage()
    ]);
}
?> 