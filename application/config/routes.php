<?php

$config['_default'] = "page/home";
$config['home'] = "page/home";
$config['login'] = "user/login";
$config['register'] = "user/register";
$config['logout'] = "user/logout";
$config['shoutbox'] = "page/shoutbox";
$config['shoutbox_external'] = "page/shoutbox_external";
$config['guide'] = "page/guide";
$config['updates'] = "update/index";

$config['about'] = "page/about";
$config['tutorial'] = "page/tutorial";
$config['closed'] = "page/closed";
$config['error'] = "page/error";

$config['locale/([a-zA-Z_]+)'] = "page/locale/$1";
$config['design/([a-zA-Z_]+)'] = "page/design/$1";

$config['users'] = "user/index";
$config['users/([0-9a-z]+)'] = "user/view/$1";
$config['users/([0-9a-z]+)/([a-z]+)'] = "user/view/$1/$2";
$config['users/edit'] = "user/edit";
$config['users/delete'] = "user/delete";

$config['chocobos'] = "chocobo/index";
$config['chocobos/([0-9]+)'] = "chocobo/view/$1";
$config['chocobos/([0-9]+)/([0-9a-zA-Z-_]+)'] = "chocobo/view/$1";

$config['races'] = "race/index";
$config['races/([0-9]+)'] = "race/view/$1";
$config['races/([0-9]+)/register'] = "race/register/$1";
$config['races/([0-9]+)/unregister'] = "race/unregister/$1";
$config['races/delete'] = "race/delete";

// ADMIN
$config['admin/users'] = "admin/user";

