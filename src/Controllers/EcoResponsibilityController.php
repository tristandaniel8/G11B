<?php

class EcoResponsibilityController {
    
    public function __construct() {
        // Aucune initialisation particulière nécessaire
    }
    
    /**
     * Affiche la page d'écoresponsabilité
     */
    public function index() {
        require_once __DIR__ . '/../Views/eco_responsibility.php';
    }
} 