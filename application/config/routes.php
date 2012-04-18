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

$config['races'] = "race/index";
$config['races/([0-9]+)'] = "race/view/$1";
$config['races/([0-9]+)/register'] = "race/register/$1";
$config['races/([0-9]+)/unregister'] = "race/unregister/$1";
$config['races/delete'] = "race/delete";

// ADMIN
$config['admin/circuits'] = "admin/circuit";
$config['admin/circuits/add'] = "admin/circuit/edit/0";
$config['admin/circuits/([0-9]+)/edit'] = "admin/circuit/edit/$1";

