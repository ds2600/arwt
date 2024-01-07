CREATE TABLE `PUBACC_SF` (
  `record_type` char(2) DEFAULT NULL,
  `unique_system_identifier` decimal(9,0) DEFAULT NULL,
  `uls_file_number` char(14) DEFAULT NULL,
  `ebf_number` varchar(30) DEFAULT NULL,
  `callsign` char(10) DEFAULT NULL,
  `lic_freeform_cond_type` char(1) DEFAULT NULL,
  `unique_lic_freeform_id` decimal(9,0) DEFAULT NULL,
  `sequence_number` int(11) DEFAULT NULL,
  `lic_freeform_condition` varchar(255) DEFAULT NULL,
  `status_code` char(1) DEFAULT NULL,
  `status_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;