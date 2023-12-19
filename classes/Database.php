<?php
/*
 * This file is part of the Amateur Radio Website Template (ARWT).
 * (c) 2024 William Bunce
 * Licensed under the MIT License. See LICENSE file in the project root for full license information.
 */

class Database
{
	private $db_host = '';
	private $db_user = '';
	private $db_pass = '';
	private $db_name = '';
	private $conn = '';

	public function __construct($db_host, $db_user, $db_pass, $db_name)
	{
		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_name = $db_name;
	}

	public function connect() {
		$this->conn = null;

		try {
			$this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
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
