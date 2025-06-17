-- This table stores the CURRENT desired speed for each motor.
-- It is the "parent" table.
DROP TABLE IF EXISTS `motors`;
CREATE TABLE `motors` (
  `motorId` INT NOT NULL,
  `motorSpeed` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`motorId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- This table logs every change made to a motor's speed.
-- It is the "child" table, linked to the parent.
DROP TABLE IF EXISTS `speed_updates`;
CREATE TABLE `speed_updates` (
  `updateId` INT NOT NULL AUTO_INCREMENT,
  `motorId` INT NOT NULL,
  `updateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `newSpeed` INT NOT NULL,
  PRIMARY KEY (`updateId`),
  KEY `motorId_idx` (`motorId`), -- Add an index for performance
  
  CONSTRAINT `fk_motor_id`
    FOREIGN KEY (`motorId`)
    REFERENCES `motors` (`motorId`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Let's add a first motor to control.
-- We will use motorId = 1 in the scripts.
INSERT INTO `motors` (`motorId`, `motorSpeed`) VALUES (1, 0);

INSERT INTO `speed_updates` (`motorId`, `newSpeed`) VALUES (1, 5);