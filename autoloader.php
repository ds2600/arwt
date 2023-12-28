<?php

spl_autoload_register(function ($className) {
    $base_dir = __DIR__ . '/classes/';

    $prefix = 'ds2600\\ARWT\\';
    $prefixLength = strlen($prefix);
    if (strncmp($prefix, $className, $prefixLength) !== 0) {
        return;
    }

    $relative_class = substr($className, $prefixLength);

    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
