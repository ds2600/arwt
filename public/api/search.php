<?php
 // Start session to limit search requests
session_start();

// Composer autoloader
require __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// ARWT legacy autoloader
require __DIR__ . '/../../autoloader.php';

use ds2600\ARWT\SearchHandler;

// Load configuration
$config = require __DIR__ . '/../../config/config.php';
$searchHandler = new SearchHandler($config);

$callSign = isset($_GET['call-sign']) ? $_GET['call-sign'] : '';
$results = $searchHandler->performSearch($callSign);

header('Content-Type: application/json');
echo json_encode($results);
