<?php

namespace ds2600\ARWT;

class SearchHandler {
    private $config;
    private $db;

    public function __construct($config) {
        $this->config = $config;
        $this->db = new Database($config);
    }

    public function performSearch($callSign) {
        if ($this->hasReachedSearchLimit()) {
            return ['error' => 'Search limit reached. Please try again later.'];
        }   
        if ($this->config['debug']) {
            error_log('Searching for ' . $callSign,0);
        }
      
        return !empty($callSign) ? $this->db->searchCallSign($callSign) : [];
    }

    public function performNameSearch($name) {
        if ($this->hasReachedSearchLimit()) {
            return ['error' => 'Search limit reached. Please try again later.'];
        }
        if ($this->config['debug']) {
            error_log('Searching for ' . $name,0);
        }

        return !empty($name) ? $this->db->searchName($name) : [];
    }

    private function hasReachedSearchLimit() {
        if (!isset($_SESSION['search_count'])) {
            $_SESSION['search_count'] = 0;
            $_SESSION['search_start_time'] = time();
        }

        if (time() - $_SESSION['search_start_time'] >= 3600) {
            $_SESSION['search_count'] = 0;
            $_SESSION['search_start_time'] = time();
        }

        if (!$this->config['debug']) {
            $_SESSION['search_count']++;
        } else {
            error_log('Search limit disabled',0);
        }

        return $_SESSION['search_count'] > $this->config['uls_search_limit'];
    }
}
