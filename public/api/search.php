<?php
 // Start session to limit search requests
session_start();

$config = require __DIR__ . '/../../config/config.php';

if ($config['api'] === 'remote') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'API not supported']);
    exit;
}

// Composer autoloader
require __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// ARWT legacy autoloader
require __DIR__ . '/../../autoloader.php';

use ds2600\ARWT\SearchHandler;

if (empty($_GET['call-sign']) && empty($_GET['name'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'required']);
    exit;
}

$searchHandler = new SearchHandler($config);

if (isset($_GET['call-sign'])) {
    $callSign = isset($_GET['call-sign']) ? $_GET['call-sign'] : '';
    $results = $searchHandler->performSearch($callSign);
} elseif (isset($_GET['name'])) {
    $name = isset($_GET['name']) ? $_GET['name'] : '';
    $results = $searchHandler->performNameSearch($name);
} else {
    $results = [];
}

header('Content-Type: application/json');
echo json_encode($results);
