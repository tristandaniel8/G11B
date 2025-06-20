-- Script pour créer les tables nécessaires au suivi des changements de statut du moteur

-- Table principale des moteurs
CREATE TABLE IF NOT EXISTS motor_data (
    motorId INT AUTO_INCREMENT PRIMARY KEY,
    motorSpeed INT NOT NULL DEFAULT 0 COMMENT 'Vitesse du moteur de 0 à 10'
);

-- Table pour stocker l'historique des mises à jour de vitesse
CREATE TABLE IF NOT EXISTS motor_updates (
    updateId INT AUTO_INCREMENT PRIMARY KEY,
    motorId INT NOT NULL,
    updateTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    newSpeed INT NOT NULL COMMENT 'Nouvelle vitesse du moteur de 0 à 10',
    
    -- Contrainte de clé étrangère
    FOREIGN KEY (motorId) REFERENCES motor_data(motorId)
);

-- Insérer un moteur par défaut si la table est vide
INSERT INTO motor_data (motorId, motorSpeed)
SELECT 1, 0 FROM dual
WHERE NOT EXISTS (SELECT 1 FROM motor_data WHERE motorId = 1); 