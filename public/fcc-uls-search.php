<?php
/*
 * This file is part of the Amateur Radio Website Template (ARWT).
 * (c) 2024 William Bunce
 * Licensed under the MIT License. See LICENSE file in the project root for full license information.
 */

require __DIR__ . '/../autoloader.php';

$config = require __DIR__ . '/../config/config.php';

if (!$config['uls_search']) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FCC ULS Search</title>
    <link rel="stylesheet" type="text/css" href="/css/arwt.css">
    <link rel="stylesheet" type="text/css" href="/css/custom.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../common/sidebar.php'; ?>
    
    <!-- Content -->
    <div class="content">
        <h1>FCC ULS Search</h1>
        <div class="search-notice">
            Searches are limited to <?php echo htmlspecialchars($config['uls_search_limit']); ?> per hour<br>
        </div>
        <div id="search-form">
            <label for="call-sign">Call Sign or Name:</label>
            <input type="text" id="call-sign" name="call-sign">
            <button id="call-sign-button" onclick="performCallSignSearch()">Search Call Sign</button>
            <button id="name-button" onclick="performNameSearch()">Search Name</button>
            <button onclick="clearHistory()">Clear</button>
            <div id="loading-indicator" class="hidden">...</div>
        </div>
        <div id="search-results" style="padding-bottom: 2rem;">
            <!-- Search results will be displayed here -->
        </div>
    </div>
    <!-- JS for search function -->
    <script src="/js/search.js"></script>
</body>
</html>
