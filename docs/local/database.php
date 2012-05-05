<?php 

$config['dev'] = array
(
	'benchmark'     => TRUE,
	'persistent'    => FALSE,
	'connection'    => array
	(
		'type'     => 'mysql',
		'user'     => 'root',
		'pass'     => 'root',
		'host'     => 'localhost',
		'port'     => FALSE,
		'socket'   => FALSE,
		'database' => 'chocobo-riding'
	),
	'character_set' => 'utf8',
	'table_prefix'  => '',
	'object'        => TRUE,
	'cache'         => TRUE,
	'escape'        => TRUE
);