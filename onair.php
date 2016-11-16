<?php
/*
Radio-NowPlaying	
Copyright (C) 2016 Stéphane Lepin <stephane.lepin@gmail.com>

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 3.0 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
Lesser General Public License for more details.
	
You should have received a copy of the GNU Lesser General Public
License along with this library. If not, see <https://www.gnu.org/licenses/>
*/

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
