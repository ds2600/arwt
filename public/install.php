<?php
    require __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    require __DIR__ . '/../autoloader.php';

    use ds2600\ARWT\DataHandler;

    $config = require __DIR__ . '/../config/config.php';
    $handler = new DataHandler($config);

    echo "<h1>ARWT Setup</h1>";
    if ($handler->getInstallStatus()) {
        echo "<p>Initial setup is complete.</p>";
        echo "<p><a href=\"/\">Click here to go to the homepage</a></p>";
        exit;
    }
    if (!isset($_GET['start'])) {
        echo "<p>The speed of the installation is based a lot on the internet speed of the server.</p>
        <p>This will create the necessary tables in your database, so ensure your <code>.env</code> file is correct.</p> 
        <p>Installation also downloads the latest weekly FCC ULS file(s). The FCC usually uploads those on Saturday, for that reason it is recommended<br>that you run this on a Sunday.</p>
        <p>Average time for installation is ~5-10 minutes.</p>
        <p><strong>If you refresh or stop/exit this page, you WILL break things and need to manually fix it.</strong></p>";
        echo "<p><a href=\"?start\">Click here to start</a></p>";
    } else {
        echo "<h2>DO NOT REFRESH OR EXIT THIS PAGE</h2>";
        echo "<p><strong>Do not refresh or exit this page until you see \"Initial setup complete\".</strong></p>";
        echo "Beginning setup...<br>";
        ob_flush();
        flush();
        $handler->initialSetup();
    }