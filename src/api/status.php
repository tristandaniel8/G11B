<?php
/**
 * API pour récupérer l'état actuel du système
 */

// Inclure les fichiers nécessaires
require_once '../config/config.php';
require_once '../models/SensorModel.php';

// Configurer les en-têtes pour permettre l'accès AJAX
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Vérifier que la méthode est GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

try {
    // Créer une instance du modèle
    $sensorModel = new SensorModel();
    
    // Récupérer les dernières données
    $latestData = $sensorModel->getLatestData();
    
    // Récupérer l'historique récent (10 dernières entrées)
    $recentHistory = $sensorModel->getHistory(10);
    
    // Formater les données pour la réponse JSON
    $response = [
        'success' => true,
        'buttonStatus' => $latestData['button_status'] === 'Appuyé',
        'motorStatus' => (bool)$latestData['motor_status'],
        'ledStatus' => (bool)$latestData['led_status'],
        'potentiometerValue' => (int)$latestData['potentiometer_value'],
        'timestamp' => $latestData['timestamp'],
        'recentHistory' => []
    ];
    
    // Formater l'historique récent
    foreach ($recentHistory as $item) {
        $type = '';
        $value = '';
        
        // Déterminer le type et la valeur
        if (isset($item['button_status']) && $item['button_status'] !== null) {
            $type = 'button';
            $value = $item['button_status'];
        } elseif (isset($item['motor_status']) && $item['motor_status'] !== null) {
            $type = 'motor';
            $value = $item['motor_status'] ? 'Activé' : 'Désactivé';
        } elseif (isset($item['led_status']) && $item['led_status'] !== null) {
            $type = 'led';
            $value = $item['led_status'] ? 'Allumée' : 'Éteinte';
        } elseif (isset($item['potentiometer_value']) && $item['potentiometer_value'] !== null) {
            $type = 'potentiometer';
            $value = $item['potentiometer_value'];
        }
        
        $response['recentHistory'][] = [
            'id' => $item['id'],
            'timestamp' => $item['timestamp'],
            'type' => $type,
            'value' => $value
        ];
    }
    
    // Envoyer la réponse
    echo json_encode($response);
    
} catch (Exception $e) {
    // En cas d'erreur, renvoyer un message d'erreur
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des données: ' . $e->getMessage()
    ]);
}
?> 