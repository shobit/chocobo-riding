<?php

/**
 * Driver to use for authentication. By default, File and ORM (default) are available.
 */
$config['driver'] = 'ORM';
$config['hash_method'] = 'sha1';
$config['salt_pattern'] = '1, 3, 5, 9, 14, 15, 16, 21, 27, 30';  // this should always be changed
$config['lifetime'] = 1209600;

?>