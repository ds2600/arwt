<?php
/*
 * This file is part of the Amateur Radio Website Template (ARWT).
 * (c) 2024 William Bunce
 * Licensed under the MIT License. See LICENSE file in the project root for full license information.
 */

require_once __DIR__ . '/../bootstrap.php';

if (!$config['uls_search']) {
    header('Location: index.php');
    exit;
}

include __DIR__ . '/../common/header.php';

if (file_exists(__DIR__ . '/install.php') && $_ENV['ENVIRONMENT'] !== 'dev') {
    $content = "<div class=\"alert alert-danger\">DELETE THE INSTALL.PHP FILE</div>";
    echo "<style>body { margin-top:50px; }</style>";
}

$content .= "<div id=\"renderedContent\" class=\"content\" >";
$content .= "<h1>FCC ULS Search</h1>";
$content .= "<div class=\"search-notice\">";
$content .= "Searches are limited to " . htmlspecialchars($config['uls_search_limit']) . " per hour<br>";
$content .= "</div>";
$content .= "<div id=\"search-form\">";
$content .= "<label for=\"call-sign\">Call Sign:</label>";
$content .= "<input type=\"text\" id=\"call-sign\" name=\"call-sign\">";
$content .= "<button onclick=\"performSearch()\">Search</button>";
$content .= "<div id=\"loading-indicator\" class=\"hidden\">...</div>";
$content .= "</div>";
$content .= "<div id=\"search-results\">";
$content .= "</div>";
$content .= "</div>";

echo "<body>";

echo "<div class=\"page-container\">";
echo "<div class=\"content-wrapper\">";
include __DIR__ . '/../common/sidebar.php';
echo "<main>";
echo $content;
echo "</main>";
echo "</div>";
include __DIR__ . '/../common/footer.php';
echo "</div>";
echo "<script src=\"/js/search.js\"></script>";
echo "</body></html>";
