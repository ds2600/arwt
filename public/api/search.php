<?php
session_start(); // Start session to limit search requests
require_once __DIR__ . '/../../autoloader.php';
use ds2600\ARWT\SearchHandler;


$config = require __DIR__ . '/../../config/config.php';
$searchHandler = new SearchHandler($config);
$searchHandler->connectToDatabase();

$callSign = isset($_GET['call-sign']) ? $_GET['call-sign'] : '';
$results = $searchHandler->performSearch($callSign);

header('Content-Type: application/json');
echo json_encode($results);
