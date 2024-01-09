<?php
// bootstrap.php

require __DIR__ . '/vendor/autoload.php';

// Instantiate Dotenv for environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Custom class autoloader
require __DIR__ . '/autoloader.php';

// Load configuration
$config = require __DIR__ . '/config/config.php';