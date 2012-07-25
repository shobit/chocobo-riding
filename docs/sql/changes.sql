ALTER TABLE  `users` CHANGE  `boxes`  `boxes` INT( 3 ) UNSIGNED NOT NULL DEFAULT  '0',
CHANGE  `items`  `items` INT( 3 ) UNSIGNED NOT NULL DEFAULT  '0',
CHANGE  `shop`  `shop` INT( 3 ) UNSIGNED NOT NULL DEFAULT  '0'

UPDATE users SET boxes = boxes -2,
items = ( items -10 ) /2