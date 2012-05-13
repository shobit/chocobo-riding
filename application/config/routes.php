<?php

$config['_default'] = "page/home";
$config['home'] = "page/home";

$config['about'] = "page/about";
$config['shoutbox'] = "page/shoutbox";
$config['tutorial'] = "page/tutorial";
$config['closed'] = "page/closed";
$config['error'] = "page/error";

$config['locale/([a-zA-Z_]+)'] = "page/locale/$1";
$config['design/([a-zA-Z_]+)'] = "page/design/$1";

$config['users/login'] = "user/login";
$config['users/new'] = "user/register";
$config['users/logout'] = "user/logout";

$config['users/([0-9]+)'] = "user/view/$1";
$config['users/([0-9]+)/([0-9a-zA-Z-_]+)'] = "user/view/$1";
$config['users/edit'] = "user/edit";
$config['users/delete'] = "user/delete";

$config['chocobos/([0-9]+)'] = "chocobo/view/$1";
$config['chocobos/([0-9]+)/([0-9a-zA-Z-_]+)'] = "chocobo/view/$1";

$config['races'] = "race/index";
$config['races/([0-9]+)'] = "race/view/$1";
$config['races/([0-9]+)/register'] = "race/register/$1";
$config['races/([0-9]+)/unregister'] = "race/unregister/$1";
$config['races/delete'] = "race/delete";

// ADMIN
$config['admin/users'] = "admin/user";

