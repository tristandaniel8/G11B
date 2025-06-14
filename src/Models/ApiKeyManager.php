<?php

class ApiKeyManager {
    private string $apiKeysFile;
    
    public function __construct() {
        // Stocker le fichier de clés API en dehors du répertoire web
        $this->apiKeysFile = dirname(__DIR__) . '/api_keys.json';
        
        // Créer le fichier s'il n'existe pas
        if (!file_exists($this->apiKeysFile)) {
            file_put_contents($this->apiKeysFile, json_encode(['weather_api_key' => '']));
            chmod($this->apiKeysFile, 0600); // Permissions restrictives
        }
    }
    
    /**
     * Récupère une clé API par son nom
     * 
     * @param string $keyName Nom de la clé API
     * @return string Clé API ou chaîne vide si non trouvée
     */
    public function getApiKey(string $keyName): string {
        if (!file_exists($this->apiKeysFile)) {
            return '';
        }
        
        $keys = json_decode(file_get_contents($this->apiKeysFile), true);
        return $keys[$keyName] ?? '';
    }
    
    /**
     * Enregistre une clé API
     * 
     * @param string $keyName Nom de la clé API
     * @param string $keyValue Valeur de la clé API
     * @return bool Succès ou échec
     */
    public function saveApiKey(string $keyName, string $keyValue): bool {
        $keys = [];
        
        if (file_exists($this->apiKeysFile)) {
            $keys = json_decode(file_get_contents($this->apiKeysFile), true);
        }
        
        $keys[$keyName] = $keyValue;
        
        return file_put_contents($this->apiKeysFile, json_encode($keys)) !== false;
    }
} 