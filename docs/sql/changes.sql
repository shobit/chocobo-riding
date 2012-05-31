CREATE TABLE  `chocobo-riding`.`vegetable_effects` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`vegetable_id` INT UNSIGNED NOT NULL ,
`name` INT UNSIGNED NOT NULL ,
`value` INT UNSIGNED NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

ALTER TABLE  `vegetables` CHANGE  `value`  `level` INT( 11 ) NOT NULL DEFAULT  '0'

ALTER TABLE  `vegetable_effects` CHANGE  `name`  `name` VARCHAR( 10 ) NOT NULL

ALTER TABLE  `users` ADD  `shop` INT UNSIGNED NOT NULL DEFAULT  '0' AFTER  `items`

ALTER TABLE  `nuts` DROP  `level` ,
DROP  `speed` ,
DROP  `intel` ,
DROP  `endur` ,
DROP  `colour` ;