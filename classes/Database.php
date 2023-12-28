<?php
/*
 * This file is part of the Amateur Radio Website Template (ARWT).
 * (c) 2024 William Bunce
 * Licensed under the MIT License. See LICENSE file in the project root for full license information.
 */

namespace ds2600\ARWT;
use PDO;

class Database
{
	private $conn = null;
	private $config = null;

	public function __construct($config)
	{
		$this->config = $config;
	}

	public function connect() {
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

	public function select($query) {
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	public function searchCallSign($callSign) {
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
