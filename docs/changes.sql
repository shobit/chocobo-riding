ALTER TABLE  `chocobos` CHANGE  `energy`  `hp` INT( 11 ) NOT NULL DEFAULT  '0',
CHANGE  `spirit`  `mp` INT( 11 ) NOT NULL DEFAULT  '0'

UPDATE chocobos SET nb_rides = nb_rides + nb_compets + nb_trainings

ALTER TABLE  `chocobos` DROP  `nb_trainings` ,
DROP  `nb_compets` ;

ALTER TABLE  `chocobos` CHANGE  `nb_rides`  `nb_races` INT( 11 ) NOT NULL DEFAULT  '0'

ALTER TABLE  `circuits` ADD  `script` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULLIF

ALTER TABLE  `circuits` DROP  `surface`

