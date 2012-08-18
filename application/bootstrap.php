<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;

if (is_file(APPPATH.'classes/kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'fr_FR.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('fr');

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
	'base_url' => (Kohana::$environment === Kohana::PRODUCTION) ? '/': '/chocobo-riding/www/',
	'index_file' => '',
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	 'auth'       => MODPATH.'auth',       // Basic authentication
	// 'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	 'database'   => MODPATH.'database',   // Database access
	// 'image'      => MODPATH.'image',      // Image manipulation
	 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	// 'unittest'   => MODPATH.'unittest',   // Unit testing
	// 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	 'pagination'  => MODPATH.'pagination',  // Pagination
	 'markdown'  => MODPATH.'markdown',  // Markdown
	 'email'  => MODPATH.'email',  // Email
	));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

Route::set('pages', '<action>',
	array(
		'action' => '(home|about|developers|help|shoutbox)'
	))
	->defaults(array(
		'controller' => 'page'
	));

Route::set('users', '<action>',
	array(
		'action' => '(login|logout|register)'
	))
	->defaults(array(
		'controller' => 'user'
	));

Route::set('mail_verify', 'mail/verify/<hash>',
	array(
		'hash' => '.*',
	))
	->defaults(array(
		'controller' => 'user',
		'action' => 'verify',
	));

Route::set('race_actions', 'races/<id>/<action>',
	array(
		'action' => '(register|unregister)',
		'id' => '[0-9]+',
	))
	->defaults(array(
		'controller' => 'race',
	));

Route::set('page_index', '<controller>s',
	array(
		'controller' => '(user|chocobo|race)',
	))
	->defaults(array(
		'action' => 'index',
	));

Route::set('page_edit', '<controller>s/<id>/edit',
	array(
		'controller' => '(user|chocobo|message)',
		'id' => '[0-9]+'
	))
	->defaults(array(
		'action' => 'edit',
	));

Route::set('page_boost', '<controller>s/<id>/boost/<apt>',
	array(
		'controller' => '(chocobo|user)',
		'id' => '[0-9]*',
		'apt' => '.*',
	))
	->defaults(array(
		'action' => 'boost'
	));

Route::set('chocobo_change', 'chocobos/<id>/change',
	array(
		'id' => '[0-9]+'
	))
	->defaults(array(
		'controller' => 'chocobo',
		'action' => 'change',
	));

Route::set('result_delete', 'results/<id>/delete',
	array(
		'id' => '[0-9]+'
	))
	->defaults(array(
		'controller' => 'result',
		'action' => 'delete',
	));

Route::set('page_new', '<controller>s/new',
	array(
		'controller' => '(discussion|message)',
	))
	->defaults(array(
		'action' => 'new',
	));

Route::set('page_view', '<controller>s/(<id>(/<section>))',
	array(
		'controller' => '(user|chocobo|race|discussion)',
		'id' => '[0-9]*',
		'section' => '.*',
	))
	->defaults(array(
		'action' => 'view',
	));

Route::set('discussions', 'discussions')
	->defaults(array(
		'controller' => 'discussion',
	));

Route::set('home', '')
	->defaults(array(
		'controller' => 'page',
		'action'     => 'home',
	));

Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'welcome',
		'action'     => 'index',
	));
