<?php 

/*
 * File: Session
 *
 * Options:
 *  driver         - Session driver name: 'cookie','database', 'native' or 'cache'
 *  storage        - Session storage parameter, used by drivers (database and cache)
 *  name           - Default session name (alpha numeric chars only and the underscore)
 *  validate       - Session parameters to validate (user_agent, ip_address)
 *  encryption     - Encryption key, set to FALSE to disable session encryption
 *  expiration     - Number of seconds that each session will last (set to 0 for session which expires on browser exit)
 *  regenerate     - Number of page loads before the session is regenerated (set to 0 to disable automatic regeneration)
 *  gc_probability - Percentage probability that garbage collection will be executed
 */
$config = array
(
	'driver'         => 'cookie',
	'storage'        => '',
	'name'           => 'cr_session',
	'validate'       => array('user_agent'),
	'encryption'     => FALSE,
	'expiration'     => 7200,
	'regenerate'     => 3,
	'gc_probability' => 2
);

?>