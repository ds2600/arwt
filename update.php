<?php
    require __DIR__ . '/vendor/autoload.php';
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
    $dotenv->load();
    
    require __DIR__ . '/autoloader.php';

    use ds2600\ARWT\DataHandler;

    $config = require __DIR__ . '/config/config.php';

    $handler = new DataHandler($config);

    $handler->updateDailyData();