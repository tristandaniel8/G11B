-- Création des tables
DROP TABLE IF EXISTS sensor_data;
DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'security') NOT NULL DEFAULT 'security',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    button_status VARCHAR(20) NOT NULL,
    motor_status BOOLEAN NOT NULL,
    led_status BOOLEAN NOT NULL,
    potentiometer_value INT NOT NULL
);

-- Vider les tables pour éviter les doublons
TRUNCATE TABLE sensor_data;

-- Insérer un utilisateur admin s'il n'existe pas déjà
INSERT INTO users (username, password, role) 
VALUES ('admin', '$2y$10$5KlAVXY0KIXrPMwX4Hg/zOUPDLFqTLbSUZnbXQZ9Ky7t1HgQVFRKK', 'admin');

-- Insérer un utilisateur security s'il n'existe pas déjà
INSERT INTO users (username, password, role)
VALUES ('security', '$2y$10$gvuKvkMkwNe.VQIHnTgRKuWw2Z6JGfJ7Qo0Ov/x5vDLCwj3QYzNv2', 'security');

-- Insérer des données de test pour les capteurs (dernières 24 heures)
-- Nous allons insérer une donnée toutes les heures pour simplifier

-- Heure actuelle - 24 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 24 HOUR), 'Non Appuyé', 0, 0, 200);

-- Heure actuelle - 23 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 23 HOUR), 'Non Appuyé', 0, 1, 300);

-- Heure actuelle - 22 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 22 HOUR), 'Appuyé', 1, 1, 500);

-- Heure actuelle - 21 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 21 HOUR), 'Appuyé', 1, 1, 600);

-- Heure actuelle - 20 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 20 HOUR), 'Non Appuyé', 0, 0, 400);

-- Heure actuelle - 19 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 19 HOUR), 'Non Appuyé', 0, 0, 300);

-- Heure actuelle - 18 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 18 HOUR), 'Appuyé', 1, 1, 700);

-- Heure actuelle - 17 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 17 HOUR), 'Appuyé', 1, 1, 800);

-- Heure actuelle - 16 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 16 HOUR), 'Non Appuyé', 0, 1, 450);

-- Heure actuelle - 15 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 15 HOUR), 'Non Appuyé', 0, 0, 350);

-- Heure actuelle - 14 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 14 HOUR), 'Non Appuyé', 0, 0, 250);

-- Heure actuelle - 13 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 13 HOUR), 'Appuyé', 1, 1, 600);

-- Heure actuelle - 12 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 12 HOUR), 'Appuyé', 1, 1, 750);

-- Heure actuelle - 11 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 11 HOUR), 'Non Appuyé', 0, 1, 500);

-- Heure actuelle - 10 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 10 HOUR), 'Non Appuyé', 0, 0, 400);

-- Heure actuelle - 9 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 9 HOUR), 'Non Appuyé', 0, 0, 300);

-- Heure actuelle - 8 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 8 HOUR), 'Appuyé', 1, 1, 650);

-- Heure actuelle - 7 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 7 HOUR), 'Appuyé', 1, 1, 800);

-- Heure actuelle - 6 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 6 HOUR), 'Non Appuyé', 0, 1, 500);

-- Heure actuelle - 5 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 5 HOUR), 'Non Appuyé', 0, 0, 400);

-- Heure actuelle - 4 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 4 HOUR), 'Appuyé', 1, 1, 700);

-- Heure actuelle - 3 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 3 HOUR), 'Appuyé', 1, 1, 900);

-- Heure actuelle - 2 heures
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 2 HOUR), 'Non Appuyé', 0, 1, 600);

-- Heure actuelle - 1 heure
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (DATE_SUB(NOW(), INTERVAL 1 HOUR), 'Non Appuyé', 0, 0, 400);

-- Heure actuelle
INSERT INTO sensor_data (timestamp, button_status, motor_status, led_status, potentiometer_value)
VALUES (NOW(), 'Appuyé', 1, 1, 800); 