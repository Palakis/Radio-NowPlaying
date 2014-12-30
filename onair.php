<?php
mb_internal_encoding("UTF-8");

spl_autoload_register(function($className) {
	$filename = str_replace('_', DIRECTORY_SEPARATOR, $className);
	$filename = 'lib/'.$filename.'.php';
	if(file_exists($filename)) {
		require_once($filename);
	}
});

require_once 'config.php';

try {
	// Do not touch this
	$onair = new OnAir();
	$onair->loadConfig($config);
	$onair->run();
}
catch(Exception $ex) {
	OnAir::Log($ex->getMessage());
}

?>
