<?php
require __DIR__ . '/../autoloader.php';
$config = require __DIR__ . '/../config/config.php';
if ($config['frontend'] === false) {
    die('Frontend is disabled. Please use the API.');
}

