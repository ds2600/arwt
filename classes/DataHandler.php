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


class DataHandler {

    private $config;
    private $db;

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
    }
    //
    // Methods for all
    //
    private function downloadFile($url, $destination) {
        echo "Downloading " . $url . ". <strong>This could take awhile, please do not refresh</strong>.";
        ob_flush();
        flush();
        $source = fopen($url, 'r');
        $dest = fopen($destination, 'w');
    
        stream_copy_to_stream($source, $dest);

        fclose($source);
        fclose($dest);
        echo "Done.<br>";
        ob_flush();
        flush();
    }
    private function getRunStatus($key) {
        if (file_exists($this->statusFile)) {
            $status = json_decode(file_get_contents($this->statusFile), true);
            return isset($status[$key]) ? $status[$key] : false;
        } 
        
        return false;
    }

    private function updateRunStatus($key, $value) {
        if (file_exists($this->statusFile)) {
            $status = json_decode(file_get_contents($this->statusFile), true);
        } else {
            $status = [];
        }
        $status[$key] = $value;
        file_put_contents($this->statusFile, json_encode($status));
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

    //
    // Initial Setup Methods
    //
    public function initialSetup() {
        if (!$this->getRunStatus('initialSetupComplete')) {
            $amat_files = [
                __DIR__ . '/../sql/create_pubacc_co.sql',
                __DIR__ . '/../sql/create_pubacc_am.sql',
                __DIR__ . '/../sql/create_pubacc_en.sql',
                __DIR__ . '/../sql/create_pubacc_hd.sql',
                __DIR__ . '/../sql/create_pubacc_hs.sql',
                __DIR__ . '/../sql/create_pubacc_la.sql',
                __DIR__ . '/../sql/create_pubacc_sc.sql',
                __DIR__ . '/../sql/create_pubacc_sf.sql',
            ];
            $this->downloadWeeklyFiles();
            $this->unzipWeeklyFiles();
            $this->createTables($amat_files);
            $this->processInitialFiles('amat', __DIR__ . "/../tmp/unzipped/l_amat.zip/");

            //Commented out until GMRS is implemented
            //$this->processInitialFiles('gmrs', __DIR__ . "/../tmp/unzipped/l_gmrs.zip/");
            //$zipFiles = [__DIR__ . '/../tmp/weekly/l_amat.zip', __DIR__ . '/../tmp/weekly/l_gmrs.zip'];
            //$extractedDirs = [__DIR__ . '/../tmp/unzipped/l_amat.zip', __DIR__ . '/../tmp/unzipped/l_gmrs.zip'];

            $zipFiles = [__DIR__ . '/../tmp/weekly/l_amat.zip'];
            $extractedDirs = [__DIR__ . '/../tmp/unzipped/l_amat.zip'];
            $this->cleanupSetupFiles($zipFiles, $extractedDirs);
            
            $this->updateRunStatus('initialSetupComplete', true);
            echo "<h2>Initial setup complete</h2>";
            echo "You should be redirected, but if not, <a href=\"" . $this->config['base_url'] . "/?setupComplete\">click here</a>.";
            echo "<script>window.location = '" . $this->config['base_url'] . "/?setupComplete';</script>";
            ob_flush();
            flush();
        }
    }

    private function downloadWeeklyFiles() {
        // Download weekly files - they contain all previous FCC data.
        $weeklyHamPath = __DIR__ . '/../tmp/weekly/l_amat.zip';
        //$weeklyGMRSPath = __DIR__ . '/../tmp/weekly/l_gmrs.zip';

        $this->downloadFile($this->weeklyHam, $weeklyHamPath);
        // Commented out until GMRS is implemented
        // $this->downloadFile($this->weeklyGMRS, $weeklyGMRSPath);
    }

    private function unzipWeeklyFiles() {
        // Commented out until GMRS is implemented
        // $zipFiles = [__DIR__ . '/../tmp/weekly/l_amat.zip', __DIR__ . '/../tmp/weekly/l_gmrs.zip']; 
        $zipFiles = [__DIR__ . '/../tmp/weekly/l_amat.zip']; 
        foreach ($zipFiles as $zipFile) {
            if (file_exists($zipFile)) {
                $zip = new ZipArchive;
                if ($zip->open($zipFile) === TRUE) {
                    $fn = pathinfo($zipFile);
                    echo "Unzipping ". $fn['basename'] . ". ";
                    ob_flush();
                    flush();                    
                    $zip->extractTo(__DIR__ . '/../tmp/unzipped/' . $fn['basename']);
                    $zip->close();
                    echo "Done.<br>";
                    ob_flush();
                    flush();
                } else {
                    echo "Failed to unzip " . $zipFile . ".<br>";
                    ob_flush();
                    flush();
                }
            }
        }
    }

    private function processInitialFiles($cycle, $dir) {
        error_log("Processing files for ". $cycle,0);

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
                    echo "Importing data into " . $tableName . ". ";
                    ob_flush();
                    flush();
                    $sql = "LOAD DATA LOCAL INFILE '{$filePath}' INTO TABLE {$tableName} FIELDS TERMINATED BY '|' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
                    $this->db->connect()->exec($sql);
                    echo "Done.<br>";
                } catch (\PDOException $e) {
                    echo "Error importing data into {$tableName}: " . $e->getMessage() . "<br>";
                }
            } else {
                echo "File {$filePath} not found.<br>";
            }
    
            ob_flush();
            flush();
        }
    
        echo "Data import completed.<br>";
        ob_flush();
        flush();
    }

    private function createTables($files) {
        foreach ($files as $file) {
            $sql = file_get_contents($file);
            if ($sql !== false) {
                $this->db->connect()->exec($sql);
                echo "Executed CREATE TABLE<br>";
                ob_flush();
                flush();
            } else {
                echo "Failed to read from " . $file . "<br>";
            }
            
        }
        echo "Tables created<br>";
        ob_flush();
        flush();
    }

    private function cleanupSetupFiles($files, $directories) {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
                echo "Deleted filed: " . $file . "<br>";
                ob_flush();
                flush();
            }
        }

        foreach ($directories as $directory) {
            $this->deleteDirectory($directory);
            echo "Deleted directory: " . $directory . "<br>";
            ob_flush();
            flush();
        }
    }

    //
    // Daily Update Methods
    //

    public function updateDailyData() {
        $prevDay = strtolower(date('D', strtotime('-1 day')));
        
        //$this->downloadDailyFiles($prevDay);
        //$this->unzipDailyFiles($prevDay);
        $this->processDailyFiles($prevDay);

        $this->updateRunStatus('lastDailyUpdate', date('Y-m-d H:i:s'));
    }

    private function downloadDailyFiles($day) {
        // Dynamically construct the URLs based on the day of the week
        $dailyHamUrl = "https://data.fcc.gov/download/pub/uls/daily/l_am_" . $day . ".zip";
        $dailyGMRSUrl = "https://data.fcc.gov/download/pub/uls/daily/l_gm_" . $day . ".zip";
        
        $dailyHamPath = __DIR__ . '/../tmp/daily/l_am_' . $day . '.zip';
        $dailyGMRSPath = __DIR__ . '/../tmp/daily/l_gm_' . $day . '.zip';

        if ($this->config['debug']) {
            error_log("Downloading daily files for " . $day,0);
        }
        
        $this->downloadFile($dailyHamUrl, $dailyHamPath);
        $this->downloadFile($dailyGMRSUrl, $dailyGMRSPath);
        
        if ($this->config['debug']) {
            error_log("Download completed for " . $day,0);
        }
    }

    private function unzipDailyFiles($day) {
        $zipFiles = [__DIR__ . '/../tmp/daily/l_am_' . $day . '.zip', __DIR__ . '/../tmp/daily/l_gm_' . $day . '.zip']; // Paths to your zip files
        foreach ($zipFiles as $zipFile) {
            if (file_exists($zipFile)) {
                $zip = new ZipArchive;
                if ($zip->open($zipFile) === TRUE) {
                    $fn = pathinfo($zipFile);
                    if ($this->config['debug']) {
                        error_log("Unzipping ". $fn['basename'], 0);
                    }
                    $zip->extractTo(__DIR__ . '/../tmp/unzipped/' . $fn['basename']); // Adjust the path as needed
                    $zip->close();
                    if ($this->config['debug']) {
                        error_log("Unzipped " . $fn['basename'],0);
                    }
                } else {
                    if ($this->config['debug']) {
                        error_log("Failed to unzip $zipFile",0);
                    }
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
                    $fieldMappings[$tableName]['updateFields']
                );
            }
        }
    }

    private function processAndUpdateTable($filePath, $tableName, $fields, $updateFields) {
        if ($this->config['debug']) {
            error_log("Updating table " . $tableName,0);
        }
        if (!file_exists($filePath)) {
            if ($this->config['debug']) {
                error_log("File not found, table probably not updated on this day: " . $filePath,0);
            }
            return;
        }
    
        $handle = fopen($filePath, "r");
        if ($handle === false) {
            if ($this->config['debug']) {
                error_log("Cannot open file: " . $filePath,0);
            }
            return;
        }
    
        while (($line = fgets($handle)) !== false) {
            $data = str_getcsv($line, "|", '\"'); // Adjust delimiter and enclosure as per your data
    
            // Construct INSERT INTO ... ON DUPLICATE KEY UPDATE SQL statement
            $insertQuery = "INSERT INTO {$tableName} (" . implode(',', $fields) . ") VALUES (" . implode(',', array_fill(0, count($fields), '?')) . ")";
            $updateQuery = " ON DUPLICATE KEY UPDATE " . implode(',', array_map(function($field) {
                return "{$field} = VALUES({$field})";
            }, $updateFields));
    
            $sql = $insertQuery . $updateQuery;
    
            try {
                $stmt = $this->db->connect()->prepare($sql);
                $stmt->execute($data);
            } catch (\PDOException $e) {
                echo "Error processing line in {$tableName}: " . $e->getMessage() . "<br>";
            }
        }
    
        fclose($handle);
        if ($this->config['debug']) {
            error_log("Table " . $tableName . " updated",0);
        }
    }
    


 
}