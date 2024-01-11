<?php
/*
 * This file is part of the Amateur Radio Website Template (ARWT).
 * (c) 2024 William Bunce
 * Licensed under the MIT License. See LICENSE file in the project root for full license information.
 */

namespace ds2600\ARWT;
use PDO;
use Redis;
use PDOException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Database
{
	private $conn = null;
	private $config = null;
	private $cache = null;
	private $logger = null;

	public function __construct($config)
	{
		$this->config = $config;
		$this->initializeCache();

		$this->logger = new Logger('datahandler');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log'));
	}

	private function initializeCache() {
		if ($this->config['redis_cache']) {
			$this->cache = new Redis();
			$this->cache->connect('127.0.0.1');
		}
	}

	public function connect() {
		if ($this->conn !== null) {
			return $this->conn;
		}

		$db_host = $_ENV['DB_HOST'];
		$db_user = $_ENV['DB_USER'];
        $db_pass = $_ENV['DB_PASS'];
        $db_name = $_ENV['DB_NAME'];

		$this->logger->info("Connecting to database: " . $db_host . " " . $db_name . " " . $db_user);
		
		try {
            $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
            $options = array (
				PDO::MYSQL_ATTR_LOCAL_INFILE => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);
			$this->logger->info("Connected to database");
			$this->conn = new PDO($dsn, $db_user, $db_pass, $options);
		} catch(PDOException $e) {
			$this->logger->critical("Failed to connect to database: " . $e->getMessage());
        }

		return $this->conn;
	}

	public function searchCallSign($callSign) {

		if ($this->config['redis_cache']) {
			$cacheKey = "searchCallSign_" . md5($callSign);
			$cachedResult = $this->cache->get($cacheKey);
			
			$this->logger->debug("Redis caching enabled");
			if ($cachedResult) {
				if ($this->config['debug']) {
					$this->logger->debug("Cache hit for ". $cacheKey);
				}
				return json_decode($cachedResult, true);
			} else {
				if ($this->config['debug']) {
					$this->logger->debug("Cache miss for ". $cacheKey);
				}
				$result = $this->performDatabaseQuery($callSign);
				// Store the result in cache with a 48-hour expiration
				$this->cache->setex($cacheKey, 48 * 60 * 60, json_encode($result));
			}
		} else {
			if ($this->config['debug']) {
				$this->logger->debug("Searching for call sign: " . $callSign);
			}
			$result = $this->performDatabaseQuery($callSign);
		}
		
		return $result;
	}

	private function performDatabaseQuery($callSign) {
		$this->connect();

		$query = "SELECT 
		EN.call_sign, 
		EN.entity_name, 
		EN.street_address, 
		EN.city, 
		EN.state, 
		EN.zip_code,
		AM.operator_class, 
		AM.previous_callsign,
		SF.lic_freeform_condition
	  FROM 
	    AMAT_PUBACC_EN AS EN
		LEFT JOIN AMAT_PUBACC_AM AS AM ON EN.unique_system_identifier = AM.unique_system_identifier
		LEFT JOIN AMAT_PUBACC_SF AS SF ON EN.unique_system_identifier = SF.unique_system_identifier
	  WHERE 
		EN.call_sign LIKE :callSign";

		$stmt = $this->conn->prepare($query);
		$stmt->execute(['callSign' => "%$callSign%"]);
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $result;
	}
	
}
