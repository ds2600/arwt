<?php
require __DIR__ . '/../common/init.php';
if (!$config['uls_search']) {
    header('Location: index.php');
    exit;
}

if ($config['api'] === 'local') {
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $apiEndpoint = "$scheme://$host/";
} elseif ($config['api'] === 'remote') {
    $apiEndpoint = $config['api_endpoint'];
} else {
    echo "Invalid API configuration.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FCC ULS Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/arwt.css">
    <link rel="stylesheet" type="text/css" href="/css/custom.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../common/sidebar.php'; ?>
    
    <!-- Content -->
    <div class="content">
        <h1>License Search</h1>
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
    <script>
        window.AppConfig = {
            apiUrl: <?php echo json_encode(rtrim($apiEndpoint, '/').'/', JSON_UNESCAPED_SLASHES); ?>
        };
    </script>

    <script src="/js/search.js"></script>
</body>
</html>
