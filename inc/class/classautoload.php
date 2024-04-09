<?php
spl_autoload_register(function ($class_name) {
	$file = __DIR__."/../../layers/classes/". $class_name . "cls.php";
	if ( file_exists($file) ) {
		require_once $file;
		return true;
	}
	$file = __DIR__."/../../layers/dal/". $class_name . ".php";
	if ( file_exists($file) ) {
		require_once $file;
		return true;
	}
	$file = __DIR__."/../../layers/bll/". $class_name . ".php";
	if ( file_exists($file) ) {
		require_once $file;
		return true;
	}
	return false;

	// $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
	// if (file_exists($file)) {
	// 		require $file;
	// 		return true;
	// }
	// return false;
});
