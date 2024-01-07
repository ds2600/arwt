<?php
/*
 * This file is part of the Amateur Radio Website Template (ARWT).
 * (c) 2024 William Bunce
 * Licensed under the MIT License. See LICENSE file in the project root for full license information.
 */

namespace ds2600\ARWT;
use ZipArchive;

class DataHandler {

    private $config;
    private $db;

    private $weeklyHam = "https://data.fcc.gov/download/pub/uls/complete/l_amat.zip";
    private $weeklyGMRS = "https://data.fcc.gov/download/pub/uls/complete/l_gmrs.zip";
    private $dailyHam = "https://data.fcc.gov/download/pub/uls/daily/l_am_day.zip";
    private $dailyGMRS = "https://data.fcc.gov/download/pub/uls/daily/l_gm_day.zip";
    
    private $statusFile = __DIR__ . '/../datahandler_status.json';

    public function __construct($config) {
        $this->config = $config;
        $this->db = new Database($config);
    }

    public function initialSetup() {
        if (!$this->getRunStatus('initialSetupComplete')) {
            $sql = [
                __DIR__ . '/../sql/create_pubacc_co.sql',
                __DIR__ . '/../sql/create_pubacc_am.sql',
                __DIR__ . '/../sql/create_pubacc_en.sql',
                __DIR__ . '/../sql/create_pubacc_hd.sql',
                __DIR__ . '/../sql/create_pubacc_hs.sql',
                __DIR__ . '/../sql/create_pubacc_la.sql',
                __DIR__ . '/../sql/create_pubacc_sc.sql',
                __DIR__ . '/../sql/create_pubacc_sf.sql',
            ];
            //$this->downloadWeeklyFiles();
            $this->unzipWeeklyFiles();
            $this->createTables($sql);
            $this->processFiles();
            $this->updateRunStatus('initialSetupComplete', true);
        }
    }

    private function downloadWeeklyFiles() {
        // Download weekly files - they contain all previous FCC data.
        $weeklyHamPath = __DIR__ . '/../tmp/weekly/l_amat.zip';
        $weeklyGMRSPath = __DIR__ . '/../tmp/weekly/l_gmrs.zip';

        $this->downloadFile($this->weeklyHam, $weeklyHamPath);
        $this->downloadFile($this->weeklyGMRS, $weeklyGMRSPath);
    }

    private function downloadFiles() {
        $prevDay = strtolower(date('D', strtotime('-1 day')));

        // Dynamically construct the URLs based on the day of the week
        $dailyHamUrl = "https://data.fcc.gov/download/pub/uls/daily/l_am_" . $prevDay . ".zip";
        $dailyGMRSUrl = "https://data.fcc.gov/download/pub/uls/daily/l_gm_" . $prevDay . ".zip";
        
        $dailyHamPath = __DIR__ . '/../tmp/daily/l_am_' . $prevDay . '.zip';
        $dailyGMRSPath = __DIR__ . '/../tmp/daily/l_gm_' . $prevDay . '.zip';

        $this->downloadFile($dailyHamUrl, $dailyHamPath);
        $this->downloadFile($dailyGMRSUrl, $dailyGMRSPath);

    }

    private function downloadFile($url, $destination) {
        echo "Downloading... please wait<br>";
        ob_flush();
        flush();

        $source = fopen($url, 'r');
        $dest = fopen($destination, 'w');
    
        stream_copy_to_stream($source, $dest);

        fclose($source);
        fclose($dest);

        echo "Download completed<br>";
        ob_flush();
        
    }

    private function unzipWeeklyFiles() {
        $zipFiles = [__DIR__ . '/../tmp/weekly/l_amat.zip', __DIR__ . '/../tmp/weekly/l_gmrs.zip']; // Paths to your zip files

        foreach ($zipFiles as $zipFile) {
            if (file_exists($zipFile)) {
                $zip = new ZipArchive;
                if ($zip->open($zipFile) === TRUE) {
                    $zip->extractTo('/tmp/unzipped/'); // Adjust the path as needed
                    $zip->close();
                    echo "Unzipped $zipFile<br>";
                } else {
                    echo "Failed to unzip $zipFile<br>";
                }
            }
        }
    
        ob_flush();
        flush();
    }

    private function processFiles() {
        // Implement the logic to process the unzipped files
        // Example: Read the files and prepare data for database insertion
    }

    private function createTables($files) {
        foreach ($files as $file) {
            $sql = file_get_contents($file);
            if ($sql !== false) {
                $this->db->connect()->exec($sql);
                echo "Executed CREATE TABLE<br>";
            } else {
                echo "Failed to read from " . $file . "<br>";
            }
            
        }
        echo "Tables created<br>";
        ob_flush();
        flush();
    }

    public function updateDailyData() {
        $this->downloadFiles();
        $this->unzipFiles();
        $this->processFiles();

        $this->updateRunStatus('lastDailyUpdate', date('Y-m-d H:i:s'));
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

}