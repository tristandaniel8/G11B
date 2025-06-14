<?php
/**
 * API pour contrôler le potentiomètre
 */

// Inclure les fichiers nécessaires
require_once '../config/config.php';
require_once '../models/SensorModel.php';

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
    if (!isset($data['value'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Paramètre "value" manquant']);
        exit;
    }
    
    // Convertir la valeur en entier et vérifier qu'elle est dans la plage valide
    $value = (int)$data['value'];
    if ($value < 0 || $value > 1023) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'La valeur doit être comprise entre 0 et 1023']);
        exit;
    }
    
    // Créer une instance du modèle
    $sensorModel = new SensorModel();
    
    // Récupérer les dernières données pour conserver les autres valeurs
    $latestData = $sensorModel->getLatestData();
    
    // Mettre à jour la valeur du potentiomètre
    $result = $sensorModel->updateActuators(
        $latestData['motor_status'],
        $latestData['led_status'],
        $value
    );
    
    // Vérifier si la mise à jour a réussi
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Valeur du potentiomètre mise à jour avec succès',
            'value' => $value
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Erreur lors de la mise à jour de la valeur du potentiomètre'
        ]);
    }
    
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la mise à jour de la valeur du potentiomètre: ' . $e->getMessage()
    ]);
}
?> 