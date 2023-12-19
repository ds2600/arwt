<?php
spl_autoload_register(function ($className) {
	$path = __DIR__ . '/classes/';
	$file = $path . str_replace('\\', '/', $className) . '.php';
	if (file_exists($file)) {
		require $file;
	} else {
		echo "Class file for {$className} not found.";
	}
});
