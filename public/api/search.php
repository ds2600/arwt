<?php
session_start(); // Start session to limit search requests

require_once __DIR__ . '/../../autoloader.php';
$config = require __DIR__ . '/../../config/config.php';

// Set search count if it doesn't exist yet
if (!isset($_SESSION['search_count'])) {
    $_SESSION['search_count'] = 0;
    $_SESSION['search_start_time'] = time();
}

// Check if an hour has passed since the first search
if (time() - $_SESSION['search_start_time'] >= 3600) {
    $_SESSION['search_count'] = 0;
    $_SESSION['search_start_time'] = time();
}

// Increment search count
if (!$config['debug']) {
    $_SESSION['search_count']++;
}

// Check if search limit has been reached
if ($_SESSION['search_count'] > $config['uls_search_limit']) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Search limit reached. Please try again later.']);
    exit;
}

$db = new Database($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
$db->connect();

$callSign = isset($_GET['call-sign']) ? $_GET['call-sign'] : '';
$results = !empty($callSign) ? $db->searchCallSign($callSign) : [];

header('Content-Type: application/json');
echo json_encode($results);