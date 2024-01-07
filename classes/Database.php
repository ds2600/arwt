<?php
/*
 * This file is part of the Amateur Radio Website Template (ARWT).
 * (c) 2024 William Bunce
 * Licensed under the MIT License. See LICENSE file in the project root for full license information.
 */

namespace ds2600\ARWT;
use PDO;
use Redis;

class Database
{
	private $conn = null;
	private $config = null;
	private $cache = null;

	public function __construct($config)
	{
		$this->config = $config;
		$this->initializeCache();
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

		if ($this->config['debug']) {
			error_log("Connecting to $db_host as $db_user",0);
		}
		
		try {
            $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
            $this->conn = new PDO($dsn, $db_user, $db_pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

		return $this->conn;
	}

	public function searchCallSign($callSign) {

		if ($this->config['redis_cache']) {
			$cacheKey = "searchCallSign_" . md5($callSign);
			$cachedResult = $this->cache->get($cacheKey);
			
			if ($cachedResult) {
				if ($this->config['debug']) {
					error_log("Cache hit for ". $cacheKey,0);
				}
				return json_decode($cachedResult, true);
			} else {
				if ($this->config['debug']) {
					error_log("Cache miss for ". $cacheKey,0);
				}
				$result = $this->performDatabaseQuery($callSign);
				// Store the result in cache with a 48-hour expiration
				$this->cache->setex($cacheKey, 48 * 60 * 60, json_encode($result));
			}
		} else {
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
		PUBACC_EN AS EN
		LEFT JOIN PUBACC_AM AS AM ON EN.unique_system_identifier = AM.unique_system_identifier
		LEFT JOIN PUBACC_SF AS SF ON EN.unique_system_identifier = SF.unique_system_identifier
	  WHERE 
		EN.call_sign LIKE :callSign";

		$stmt = $this->conn->prepare($query);
		$stmt->execute(['callSign' => "%$callSign%"]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
}
