<?php
/*
 * This file is part of the Amateur Radio Website Template (ARWT).
 * (c) 2024 William Bunce
 * Licensed under the MIT License. See LICENSE file in the project root for full license information.
 */

namespace ds2600\ARWT;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use ZipArchive;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class DataHandler {

    private $config;
    private $db;
    private $logger;
    private $errorLogger;

    private $weeklyHam = "https://data.fcc.gov/download/pub/uls/complete/l_amat.zip";
    private $weeklyGMRS = "https://data.fcc.gov/download/pub/uls/complete/l_gmrs.zip";
    private $statusFile = __DIR__ . '/../datahandler_status.json';
    private $dailyFileMappings = [
        'AMAT_PUBACC_AM' => 'AM.dat',
        'AMAT_PUBACC_CO' => 'CO.dat',
        'AMAT_PUBACC_EN' => 'EN.dat',
        'AMAT_PUBACC_HD' => 'HD.dat',
        'AMAT_PUBACC_HS' => 'HS.dat',
        'AMAT_PUBACC_LA' => 'LA.dat',
        'AMAT_PUBACC_SC' => 'SC.dat',
        'AMAT_PUBACC_SF' => 'SF.dat',
    ];
   
    public function __construct($config) {
        $this->config = $config;
        $this->db = new Database($config);
        $this->logger = new Logger('datahandler');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log'));

        $this->errorLogger = new Logger('datahandler');
        $this->errorLogger->pushHandler(new StreamHandler(__DIR__ . '/../logs/uls_data_errors.log'));
    }
    //
    // Methods for all
    //
    private function downloadFile($cycle, $url, $destination) {
        if ($cycle == "weekly") {
            $this->logger->info("Downloading " . $url);
            echo "Downloading " . $url . ". <strong>This could take awhile, please do not refresh</strong>.";
            ob_flush();
            flush();
        } else {
            $this->logger->info('Downloading ' . $url);
        }
        $source = fopen($url, 'r');
        $dest = fopen($destination, 'w');
    
        stream_copy_to_stream($source, $dest);

        fclose($source);
        fclose($dest);
        if ($cycle == "weekly") {
            $this->logger->info("Download complete");
            echo "Done.<br>";
            ob_flush();
            flush();
        } else {
            $this->logger->info('Download complete');
        }
    }
    private function getRunStatus($key) {
        if (file_exists($this->statusFile)) {
            $status = json_decode(file_get_contents($this->statusFile), true);
            $this->logger->info("Current status: ". $key .":". $status["status"]);
            return isset($status[$key]) ? $status[$key] : false;
        } 
        return false;
    }

    public function getInstallStatus() {
        return $this->getRunStatus('initialSetupComplete');
    }

    private function updateRunStatus($key, $value) {
        if (file_exists($this->statusFile)) {
            $status = json_decode(file_get_contents($this->statusFile), true);
        } else {
            $status = [];
        }
        $this->logger->info("Set status: ". $key .":". $value);
        $status[$key] = $value;
        file_put_contents($this->statusFile, json_encode($status));
    }

    private function cleanupFiles($cycle, $files, $directories) {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
                $this->logger->info("Deleted file: " . $file);
                if ($cycle == "setup") {
                    echo "Deleted filed: " . $file . "<br>";                    
                    ob_flush();
                    flush();
                }
            }
        }
        foreach ($directories as $directory) {
            $this->deleteDirectory($directory);
            $this->logger->info("Deleted directory: " . $directory);
            if ($cycle == "setup") {
                echo "Deleted directory: " . $directory . "<br>";
                ob_flush();
                flush();
            }
        }
    }

    private function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return;
        }
        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir()) {
                rmdir($item->getRealPath());
            } else {
                unlink($item->getRealPath());
            }
        }
        rmdir($dir);
    }
    
    private function convertDateToMySQLFormat($date) {
        if (preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/(\d{4})$/', $date, $matches)) {
            // Convert MM/DD/YYYY to YYYY-MM-DD
            return $matches[3] . '-' . $matches[1] . '-' . $matches[2];
        }
        return null; // Return null if the date is not in the expected format
    }
    //
    // Initial Setup Methods
    //
    public function initialSetup() {
        if (!$this->getRunStatus('initialSetupComplete')) {
            $this->logger->info("Initial setup started");
            $amat_tables = [
                __DIR__ . '/../sql/create_pubacc_co.sql',
                __DIR__ . '/../sql/create_pubacc_am.sql',
                __DIR__ . '/../sql/create_pubacc_en.sql',
                __DIR__ . '/../sql/create_pubacc_hd.sql',
                __DIR__ . '/../sql/create_pubacc_hs.sql',
                __DIR__ . '/../sql/create_pubacc_la.sql',
                __DIR__ . '/../sql/create_pubacc_sc.sql',
                __DIR__ . '/../sql/create_pubacc_sf.sql',
            ];
            $zipFiles = [__DIR__ . '/../tmp/weekly/l_amat.zip'];
            $extractedDirs = [__DIR__ . '/../tmp/unzipped/l_amat.zip'];
            $this->downloadWeeklyFiles();
            $this->unzipWeeklyFiles($zipFiles);
            $this->createTables($amat_tables);
            $this->processInitialFiles('amat', __DIR__ . "/../tmp/unzipped/l_amat.zip/");

            //Commented out until GMRS is implemented
            //$this->processInitialFiles('gmrs', __DIR__ . "/../tmp/unzipped/l_gmrs.zip/");
            //$zipFiles = [__DIR__ . '/../tmp/weekly/l_amat.zip', __DIR__ . '/../tmp/weekly/l_gmrs.zip'];
            //$extractedDirs = [__DIR__ . '/../tmp/unzipped/l_amat.zip', __DIR__ . '/../tmp/unzipped/l_gmrs.zip'];

            $this->cleanupFiles("setup", $zipFiles, $extractedDirs);
            $this->updateRunStatus('initialSetupComplete', true);
            $this->logger->info("Initial setup complete");
            echo "<h2>Initial setup complete</h2>";
            echo "Your setup is completed, <a href=\"http://" . $this->config['base_url'] . "/?setupComplete\">click here</a>.";
            ob_flush();
            flush();
        }
    }

    private function downloadWeeklyFiles() {
        $this->logger->info("Downloading latest weekly file(s)");
        // Download weekly files - they contain all previous FCC data.
        // Commented out until GMRS is implemented
        //$weeklyGMRSPath = __DIR__ . '/../tmp/weekly/l_gmrs.zip';
        // $this->downloadFile($this->weeklyGMRS, $weeklyGMRSPath);
        
        $weeklyHamPath = __DIR__ . '/../tmp/weekly/l_amat.zip';
        $this->downloadFile('weekly', $this->weeklyHam, $weeklyHamPath);
    }

    private function unzipWeeklyFiles($zipFiles) {
        $this->logger->info("Unzipping latest weekly file(s)");
        foreach ($zipFiles as $zipFile) {
            if (file_exists($zipFile)) {
                $zip = new ZipArchive;
                if ($zip->open($zipFile) === TRUE) {
                    $fn = pathinfo($zipFile);
                    $this->logger->info("Unzipping " . $fn['basename']);
                    echo "Unzipping ". $fn['basename'] . ". ";
                    ob_flush();
                    flush();                    
                    $zip->extractTo(__DIR__ . '/../tmp/unzipped/' . $fn['basename']);
                    $zip->close();
                    $this->logger->info("Unzipped " . $fn['basename']);
                    echo "Done.<br>";
                    ob_flush();
                    flush();
                } else {
                    $this->logger->critical("Failed to unzip " . $zipFile);
                    echo "Failed to unzip " . $zipFile . ".<br>";
                    ob_flush();
                    flush();
                }
            }
        }
    }

    private function processInitialFiles($cycle, $dir) {
        $this->logger->info("Processing initial files");
        if ($cycle == "amat") {
            $dataFiles = [
                'AM' => 'AMAT_PUBACC_AM',
                'CO' => 'AMAT_PUBACC_CO',
                'EN' => 'AMAT_PUBACC_EN',
                'HD' => 'AMAT_PUBACC_HD',
                'HS' => 'AMAT_PUBACC_HS',
                'LA' => 'AMAT_PUBACC_LA',
                'SC' => 'AMAT_PUBACC_SC',
                'SF' => 'AMAT_PUBACC_SF',
            ];
        } else {
            // Put GMRS files here
            die();
        }
        foreach ($dataFiles as $fileKey => $tableName) {
            $filePath = $dir . $fileKey. ".dat";
            if (file_exists($filePath)) {
                try {
                    $this->logger->info("Importing data into " . $tableName);
                    echo "Importing data into " . $tableName . ". ";
                    ob_flush();
                    flush();
                    $sql = "LOAD DATA LOCAL INFILE '{$filePath}' INTO TABLE {$tableName} FIELDS TERMINATED BY '|' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
                    $this->db->connect()->exec($sql);
                    echo "Done.<br>";
                } catch (\PDOException $e) {
                    $this->logger->critical("Error importing data into " . $tableName . ": " . $e->getMessage());
                    echo "Error importing data into {$tableName}: " . $e->getMessage() . "<br>";
                }
            } else {
                $this->logger->alert("File {$filePath} not found.");
                echo "File {$filePath} not found.<br>";
            }
            ob_flush();
            flush();
        }
        $this->logger->info("Data import completed");
        echo "Data import completed.<br>";
        ob_flush();
        flush();
    }

    private function createTables($files) {
        foreach ($files as $file) {
            $sql = file_get_contents($file);
            if ($sql !== false) {
                $this->db->connect()->exec($sql);
                $this->logger->info("Executed CREATE TABLE: " . $file);
                echo "Executed CREATE TABLE<br>";
                ob_flush();
                flush();
            } else {
                $this->logger->critical("Failed to read from " . $file);
                echo "Failed to read from " . $file . "<br>";
            }
        }
        $this->logger->info("Tables created");
        echo "Tables created<br>";
        ob_flush();
        flush();
    }
    //
    // Daily Update Methods
    //
    public function updateDailyData() {
        $prevDay = strtolower(date('D', strtotime('-1 day')));
        //$zipFiles = [__DIR__ . '/../tmp/daily/l_am_' . $prevDay . '.zip', __DIR__ . '/../tmp/daily/l_gm_' . $prevDay . '.zip'];
        $zipFiles = [__DIR__ . '/../tmp/daily/l_am_' . $prevDay . '.zip'];
        $extractedDirs = [__DIR__ . '/../tmp/unzipped/l_am_' . $prevDay . '.zip'];
        $this->logger->info("Beginning daily update for ". $prevDay);
        $this->downloadDailyFiles($prevDay);
        $this->unzipDailyFiles($zipFiles);
        $this->processDailyFiles($prevDay);
        $this->cleanupFiles('daily', $zipFiles, $extractedDirs);
        $this->updateRunStatus('lastDailyUpdate', date('Y-m-d H:i:s'));
        if ($this->config['debug']) {
            error_log('Daily update complete', 0);
        }
    }

    private function downloadDailyFiles($day) {
        // Dynamically construct the URLs based on the day of the week
        $dailyHamUrl = "https://data.fcc.gov/download/pub/uls/daily/l_am_" . $day . ".zip";
        //$dailyGMRSUrl = "https://data.fcc.gov/download/pub/uls/daily/l_gm_" . $day . ".zip";
        $dailyHamPath = __DIR__ . '/../tmp/daily/l_am_' . $day . '.zip';
        $dailyGMRSPath = __DIR__ . '/../tmp/daily/l_gm_' . $day . '.zip';
        $this->downloadFile('daily', $dailyHamUrl, $dailyHamPath);
        //$this->downloadFile($dailyGMRSUrl, $dailyGMRSPath);
    }

    private function unzipDailyFiles($zipFiles) {
        $this->logger->info("Unzipping files");
        foreach ($zipFiles as $zipFile) {
            if (file_exists($zipFile)) {
                $zip = new ZipArchive;
                if ($zip->open($zipFile) === TRUE) {
                    $fn = pathinfo($zipFile);
                    $this->logger->info("Unzipping " . $fn['basename']);
                    $zip->extractTo(__DIR__ . '/../tmp/unzipped/' . $fn['basename']); 
                    $zip->close();
                    $this->logger->info("Unzipped " . $fn['basename']);
                } else {
                    $this->logger->critical("Failed to unzip " . $fn['basename']);
                }
            }
        }
    }

    private function processDailyFiles($day) {
        $fieldMappings = require(__DIR__ . '/../config/fieldMappings.php');

        foreach ($this->dailyFileMappings as $tableName => $fileName) {
            $filePath = __DIR__ . '/../tmp/unzipped/l_am_'. $day .'.zip/' . $fileName;
            if (isset($fieldMappings[$tableName])) {
                $this->processAndUpdateTable(
                    $filePath,
                    $tableName,
                    $fieldMappings[$tableName]['fields'],
                    $fieldMappings[$tableName]['updateFields'],
                    ['status_date']
                );
            }
        }
    }

    private function processAndUpdateTable($filePath, $tableName, $fields, $updateFields, $datetimeFields = []) {
        $this->logger->info("Updating table " . $tableName);
        if (!file_exists($filePath)) {
            $this->logger->warning("File not found, table probably not updated on this day", ['processAndUpdateTable']);
            return;
        }
    
        $handle = fopen($filePath, "r");
        if ($handle === false) {
            $this->logger->critical("Cannot open file: ". $filePath, ['processAndUpdateTable']);
            return;
        }
    
        while (($line = fgets($handle)) !== false) {
            $data = str_getcsv($line, "|", '\"'); 

            // Convert datetime fields from MM/DD/YYYY to YYYY-MM-DD and handle empty strings
            foreach ($datetimeFields as $datetimeField) {
                $index = array_search($datetimeField, $fields);
                if ($index !== false) {
                    if (!empty($data[$index])) {
                        $data[$index] = $this->convertDateToMySQLFormat($data[$index]);
                    } else {
                        $data[$index] = null; // Convert empty strings to NULL
                    }
                }
            }

            // Ensure $data array has the correct number of elements
            while (count($data) < count($fields)) {
                $data[] = null;
            }

            if (count($data) > count($fields)) {
                if ($this->config['debug']) {
                    $this->logger->error("Data has more fields than expected. Skipping line.", ['processAndUpdateTable',$tableName]);
                    $this->errorLogger->error(json_encode($data), ['processAndUpdateTable',$tableName]);
                }
                continue;
            }
    
            $insertQuery = "INSERT INTO {$tableName} (" . implode(',', $fields) . ") VALUES (" . implode(',', array_fill(0, count($fields), '?')) . ")";
            $updateQuery = " ON DUPLICATE KEY UPDATE " . implode(',', array_map(function($field) {
                return "{$field} = VALUES({$field})";
            }, $updateFields));
    
            $sql = $insertQuery . $updateQuery;
            try {
                $stmt = $this->db->connect()->prepare($sql);
                $stmt->execute($data);
            } catch (\PDOException $e) {
                $this->logger->critical("Error updating table " . $tableName . ": " . $e->getMessage() . " Data: " . json_encode($data), ['processAndUpdateTable']);
            }
        }
        fclose($handle);
        $this->logger->info("Table " . $tableName . " updated");
    }
}