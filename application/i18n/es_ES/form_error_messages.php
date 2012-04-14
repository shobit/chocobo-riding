<?php defined('SYSPATH') or die('No direct access allowed.');
 
$lang = array (
    'username' => array (
        'required' => 'El nombre del user es obligatorio',
        'length' => '¡El nombre del user debe tener entre 4 y 12 caracteres!',
        'username_exists' => 'El nombre del user ya está siendo utilizado por otro jugador.',
        'default' => 'Datos no válidos.',
        ),
    'password' => array (
        'required' => '¡Su contraseña debe contener al menos un caracter!',
        'default' => 'Datos no válidos.',
        ),
    'password_again' => array (
        'matches' => '¡Ha reescrito mal la contraseña!',
        'default' => 'Datos no válidos.',
        ),
    'email' => array (
        'required' => 'Su dirección de correo electrónico es obligatoria.',
        'email' => 'Su dirección de correo electrónico tiene un formato no válido.',
        'email_exists' => 'Esta dirección de correo electrónico ya está siendo utilizada por otro jugador.',
        'default' => 'Datos no válidos.',
        ),
    'title' => array(
    	'required' => "El título del tema es obligatorio.",
    	'default' => "Datos no válidos."
    ),
    'content' => array(
    	'required' => "El contenido del mensaje es obligatorio.",
    	'default' => "Datos no válidos."
    ),
    'users' => array(
    	'required' => "Los participantes deben ser informados.",
    	'users_not_valid' => "Un ou plusieurs destinaires n'existent pas.",
    	'default' => "Datos no válidos."
    )
);