<?php

// $Id: //

/**
 * @file config.php
 *
 * Global configuration variables (may be added to by other modules).
 *
 */

global $config;

// Date timezone
date_default_timezone_set('UTC');

// Environment----------------------------------------------------------------------------
// In development this is a PHP file that is in .gitignore, when deployed these parameters
// will be set on the server
if (file_exists(dirname(__FILE__) . '/env.php'))
{
	include 'env.php';
}

// CouchDB--------------------------------------------------------------------------------	
$config['couchdb_options'] = array(
	'database' 	=> 'data-citation-corpus-v2',
	'host' 		=> getenv('COUCHDB_USERNAME') . ':' . getenv('COUCHDB_PASSWORD') . '@' . getenv('COUCHDB_HOST'),
	'port' 		=> getenv('COUCHDB_PORT'),
	'prefix' 	=> getenv('COUCHDB_PROTOCOL'),		
	);	


$config['stale'] = false;
	
?>
