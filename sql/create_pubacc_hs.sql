CREATE TABLE `AMAT_PUBACC_HS` (
  `record_type` char(2) NOT NULL,
  `unique_system_identifier` decimal(9,0) NOT NULL,
  `uls_file_number` char(14) DEFAULT NULL,
  `callsign` char(10) DEFAULT NULL,
  `log_date` char(10) DEFAULT NULL,
  `code` char(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;