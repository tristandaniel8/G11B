-- This table stores the CURRENT desired speed for each motor.
-- It is the "parent" table.
DROP TABLE IF EXISTS `motors`;
CREATE TABLE `motors` (
  `motorId` INT NOT NULL,
  `motorSpeed` INT NOT NULL DEFAULT 0 COMMENT 'Speed from 0 (stopped) to 10 (fastest)',
  PRIMARY KEY (`motorId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- This table logs every change made to a motor's speed.
-- It is the "child" table, linked to the parent.
DROP TABLE IF EXISTS `speed_updates`;
CREATE TABLE `speed_updates` (
  `updateId` INT NOT NULL AUTO_INCREMENT,
  `motorId` INT NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `newSpeed` INT NOT NULL COMMENT 'New speed from 0 to 10',
  PRIMARY KEY (`updateId`),
  KEY `motorId_idx` (`motorId`), 
  
  CONSTRAINT `fk_speed_update_motor_id`
    FOREIGN KEY (`motorId`)
    REFERENCES `motors` (`motorId`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `capteur_temp_simule`;
CREATE TABLE `capteur_temp_simule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dates` datetime DEFAULT NULL,
  `valeur` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `motors` (`motorId`, `motorSpeed`) VALUES (1, 0);

-- ALTER statements for the 'users' table.
-- These will add columns if they don't exist.
-- If a column already exists, the specific ADD COLUMN statement for it will produce
-- an error (like 'Duplicate column name'), which can be ignored if the column is already as desired.

-- Attempt to add 'email' column if it doesn't exist
ALTER TABLE `users` ADD COLUMN `email` VARCHAR(255) DEFAULT NULL UNIQUE AFTER `password`;

-- Attempt to add 'reset_token_hash' column if it doesn't exist
ALTER TABLE `users` ADD COLUMN `reset_token_hash` VARCHAR(255) DEFAULT NULL AFTER `role`;

-- Attempt to add 'reset_token_expires_at' column if it doesn't exist
ALTER TABLE `users` ADD COLUMN `reset_token_expires_at` DATETIME DEFAULT NULL AFTER `reset_token_hash`;

-- Ensure the 'admin' user has an email for password reset testing.
-- This will fail if an admin user with this email already exists, which is fine.
UPDATE `users` SET `email` = 'admin@example.com' WHERE `username` = 'admin' AND `email` IS NULL;