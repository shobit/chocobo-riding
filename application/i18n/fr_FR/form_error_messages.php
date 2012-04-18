<?php defined('SYSPATH') or die('No direct access allowed.');
 
$lang = array (
    'username' => array (
        'required' => 'Le nom de votre user est obligatoire.',
        'length' => 'Le nom de votre user doit faire entre 4 et 20 caractères!',
        'username_exists' => 'Ce nom de user est déjà utilisé par un autre joueur.',
        'default' => 'Le nom de chocobo doit comporter entre 4 et 20 caractères et peut être composé de lettres, chiffres et des caractères _ et -.',
        ),
    'password' => array (
        'required' => 'Votre mot de passe doit comporter au minimum 1 caractère!',
        'default' => 'Donnée invalide.',
        ),
    'password_again' => array (
        'matches' => 'Vous avez mal recopié votre mot de passe!',
        'default' => 'Donnée invalide.',
        ),
    'email' => array (
        'required' => 'Votre adresse e-mail est obligatoire.',
        'email' => 'Votre adresse e-mail est mal formatée.',
        'email_exists' => 'Cette adresse e-mail est déjà utilisée par un autre joueur.',
        'default' => 'Donnée invalide.',
        ),
    'title' => array(
    	'required' => "Le titre du topic est obligatoire.",
    	'default' => "Donnée invalide."
    ),
    'content' => array(
    	'required' => "Le contenu du message est obligatoire.",
    	'default' => "Donnée invalide."
    ),
    'users' => array(
    	'required' => "Les participants doivent être renseignés.",
    	'users_not_valid' => "Un ou plusieurs destinaires n'existent pas.",
    	'default' => "Donnée invalide."
    ),
    'version' => array(
    	'required' => "Vous devez renseigner un numéro de version.",
    	'default' => "Donnée invalide."
    ),
    // chocobo/edit
    'name' => array(
    	'required' => "Vous devez donner absolument un nom à votre chocobo!",
    	'name_exists' => "Ce nom de chocobo est déjà pris. Choisissez un autre.",
    	'default' => "Le nom de chocobo doit comporter entre 4 et 12 caractères et peut être composé de lettres, chiffres et des caractères _ et -."
    ),
    // fusion
    'chocobo' => array(
    	'matches' => "Votre chocobo n'est pas d'humeur..."
    ),
    'partner' => array(
    	'required' => "Vous devez sélectionner un partenaire.",
    	'matches' => "Votre partenaire n'est pas d'humeur..."
    ),
    'nut' => array(
    	'required' => "Vous devez sélectionner une noix.",
    	'matches' => "Votre noix est introuvable..."
    ),
    'boxes' => array(
    	'no_boxes' => "Votre écurie n'est pas assez grande!"
    )
);