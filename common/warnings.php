<?php

if (isset($_GET['setupCompleted']) && $_ENV['ENVIRONMENT'] !== 'dev') {
    if (file_exists(__DIR__ . '/../public/install.1.php')) {
        echo "YOU SHOULD DELETE INSTALL.PHP NOW";
    }
}