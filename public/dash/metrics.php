<?php
// Composer
require __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

require __DIR__ . '/../../autoloader.php';

use ds2600\ARWT\Database;

$config = require __DIR__ . '/../../config/config.php';

// Get server info
$cpuLoad = sys_getloadavg();
$memoryUsage = memory_get_usage();
$sessionFiles = glob(session_save_path() . '/*');
// Get database info
$db = new Database($config);
$status = $db->connect()->query('SHOW GLOBAL STATUS')->fetchAll(PDO::FETCH_KEY_PAIR);

echo "<h1>ARWT Metrics</h1>";
echo "<h2>Server Info</h2>";
echo "CPU Load: " . $cpuLoad[0] . "<br>";
echo "Memory Usage: " . $memoryUsage . "<br>";
echo "<h2>Database Info</h2>";
echo 'Active Sessions: ' . count($sessionFiles) . '<br>';
echo "Connections: " . $status['Connections'] . "<br>";
echo "Uptime: " . $status['Uptime'] . " seconds<br>";
echo "Queries since start: " . $status['Queries'] . "<br>";



