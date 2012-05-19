ALTER TABLE  `users` CHANGE  `activated`  `activated` INT UNSIGNED NOT NULL DEFAULT  '0',
CHANGE  `banned`  `banned` INT UNSIGNED NOT NULL DEFAULT  '0'
ALTER TABLE  `users` CHANGE  `activated`  `activated` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0',
CHANGE  `banned`  `banned` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0'