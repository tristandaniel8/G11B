<?php

class WeatherModel {
    private string $apiKey;
    private string $city;
    private bool $apiKeyValid;
    
    public function __construct(string $apiKey, string $city = 'Paris') {
        $this->apiKey = $apiKey;
        $this->city = $city;
        $this->apiKeyValid = ($apiKey !== 'YOUR_API_KEY');
    }
    
    /**
     * Récupère les données météorologiques actuelles
     * 
     * @return array Données météo ou tableau vide en cas d'erreur
     */
    public function getCurrentWeather(): array {
        // Si la clé API n'est pas valide, retourner un tableau vide
        if (!$this->apiKeyValid) {
            return [
                'error' => 'Clé API non configurée. Veuillez configurer une clé API OpenWeatherMap valide.'
            ];
        }
        
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$this->city}&units=metric&lang=fr&appid={$this->apiKey}";
        
        try {
            // Désactiver les avertissements pour file_get_contents
            $context = stream_context_create([
                'http' => [
                    'ignore_errors' => true
                ]
            ]);
            
            $response = file_get_contents($url, false, $context);
            if ($response === false) {
                error_log("Erreur lors de la récupération des données météo");
                return ['error' => 'Impossible de récupérer les données météo'];
            }
            
            // Vérifier le code de réponse HTTP
            $http_response_header = $http_response_header ?? [];
            $status_line = $http_response_header[0] ?? '';
            
            if (strpos($status_line, '401') !== false) {
                return ['error' => 'Clé API invalide. Veuillez vérifier votre clé API OpenWeatherMap.'];
            }
            
            if (strpos($status_line, '200') === false) {
                return ['error' => 'Erreur lors de la récupération des données météo: ' . $status_line];
            }
            
            $data = json_decode($response, true);
            if (!$data) {
                error_log("Erreur lors du décodage des données météo");
                return ['error' => 'Erreur lors du décodage des données météo'];
            }
            
            return [
                'temperature' => isset($data['main']['temp']) ? round($data['main']['temp']) : null,
                'description' => isset($data['weather'][0]['description']) ? ucfirst($data['weather'][0]['description']) : null,
                'icon' => isset($data['weather'][0]['icon']) ? $data['weather'][0]['icon'] : null,
                'humidity' => isset($data['main']['humidity']) ? $data['main']['humidity'] : null,
                'wind_speed' => isset($data['wind']['speed']) ? $data['wind']['speed'] : null,
                'city' => isset($data['name']) ? $data['name'] : $this->city
            ];
        } catch (Exception $e) {
            error_log("Exception lors de la récupération des données météo: " . $e->getMessage());
            return ['error' => 'Exception: ' . $e->getMessage()];
        }
    }
    
    /**
     * Change la ville pour laquelle récupérer les données météo
     * 
     * @param string $city Nom de la ville
     */
    public function setCity(string $city): void {
        $this->city = $city;
    }
} 