<?php

$config['_default'] = "page/home";
$config['home'] = "page/home";
$config['events'] = "page/events";
$config['shoutbox'] = "page/shoutbox";
$config['tutorial'] = "page/tutorial";
$config['closed'] = "page/closed";
$config['error'] = "page/error";
$config['locale/([a-zA-Z_]+)'] = "page/locale/$1";
$config['design/([a-zA-Z_]+)'] = "page/design/$1";
$config['register'] = "user/register";

$config['circuits/([0-9]+)'] = "circuit/view/$1";
$config['circuits/([0-9]+)/register'] = "circuit/register/$1";
$config['circuits/([0-9]+)/unregister'] = "circuit/unregister/$1";
$config['circuits/delete'] = "circuit/delete";
