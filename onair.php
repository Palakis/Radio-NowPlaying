<?php
mb_internal_encoding("UTF-8");

// Register class autoloader
spl_autoload_register(function($className) {
	$filename = str_replace('_', DIRECTORY_SEPARATOR, $className);
	$filename = 'lib/'.$filename.'.php';
	if(file_exists($filename)) {
		require_once($filename);
	}
});

// Include config file
require_once 'config.php';

try {
	// Create the core object, give it the configuration array 
	// and launch it
	$onair = new OnAir($config);
	$onair->run();
}
catch(Exception $ex) {
	// Catch any error, log it to the errors.log file, and print it
	OnAir::Log($ex->getMessage());
}

?>
