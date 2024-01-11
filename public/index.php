<?php
$content = '';
require_once __DIR__ . '/../bootstrap.php';

include __DIR__ . '/../common/header.php';
if (file_exists(__DIR__ . '/install.php') && $_ENV['ENVIRONMENT'] !== 'dev') {
    $content = "<div class=\"alert alert-danger\">DELETE THE INSTALL.PHP FILE</div>";
    echo "<style>body { margin-top:50px; }</style>";
}
echo "<body>";
include __DIR__ . '/../common/sidebar.php';

function sanitizePageName($page) {
    return preg_replace('/[^a-z0-9_\-]/i', '', $page);
}

$markdownDir = __DIR__ . '/../pages/markdown/';
$htmlDir = __DIR__ . '/../pages/html/';

$page = isset($_GET['p']) ? sanitizePageName($_GET['p']) : 'home';

if (file_exists($markdownDir . $page . '.md')) {
    $content .= "<div id=\"markdownContent\" style=\"display:none;\">";
    $content .= file_get_contents($markdownDir . $page . '.md');
    $content .= "</div>";
    $content .= "<div id=\"renderedContent\" class=\"content\" >";
    $content .= "</div>";
    $content .= "<script src=\"/js/showdown.js\"></script>";
} else if (file_exists($htmlDir . $page . '.html')) {
    $content .= "<div id=\"renderedContent\" class=\"content\">";
    $content .= file_get_contents($htmlDir . $page . '.html');
    $content .= "</div>";
} else {
    $content .= "<div id=\"renderedContent\" class=\"content\">";
    $content .= file_get_contents($htmlDir . '404.html');
    $content .= "</div>";
}

echo $content;
include __DIR__ . '/../common/footer.php';