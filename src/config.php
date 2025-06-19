<?php
// Vérifier si l'extension mbstring est disponible
if (extension_loaded('mbstring')) {
    // Configuration globale
    mb_internal_encoding('UTF-8');
    mb_http_output('UTF-8');
    mb_regex_encoding('UTF-8');
}

// Définir l'encodage par défaut pour les fonctions multibyte
ini_set('default_charset', 'UTF-8');

// Fonction pour convertir les chaînes en UTF-8 si nécessaire
function ensure_utf8($string) {
    if (!is_string($string)) {
        return $string;
    }
    
    if (function_exists('mb_check_encoding')) {
        if (!mb_check_encoding($string, 'UTF-8')) {
            return function_exists('mb_convert_encoding') 
                ? mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1') 
                : utf8_encode($string);
        }
    } else {
        // Fallback si mb_string n'est pas disponible
        return utf8_encode($string);
    }
    
    return $string;
}

// Fonction pour nettoyer les données de la base de données
function clean_db_data($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = clean_db_data($value);
        }
    } else {
        $data = ensure_utf8($data);
    }
    return $data;
}

// Configuration pour l'API météo
// Remplacez 'YOUR_API_KEY' par votre clé API OpenWeatherMap
// Pour obtenir une clé API gratuite:
// 1. Créez un compte sur https://openweathermap.org/
// 2. Connectez-vous et allez dans "API keys" dans votre profil
// 3. Copiez votre clé API et collez-la ci-dessous
define('WEATHER_API_KEY', 'YOUR_API_KEY'); // À remplacer par une clé API valide
define('WEATHER_DEFAULT_CITY', 'Paris'); // Ville par défaut 