<?php

// Site Configuration
return [
    'callsign' => 'ARWT',
    'gmrs_callsign' => '',
    // Enable or disable the FCC ULS search feature - see README.md for more info
    'uls_search' => true,
    // Limit the number of ULS searches per hour
    'uls_search_limit' => 5,
    // Enable Redis Cache
    'redis_cache' => true,
    // Enable or disable debug mode
    'debug' => false,
    // Enable frontend - false provides only the API
    'frontend' => true,
    // API 'local' or 'remote'
    'api' => 'local',
    // API Endpoint - for use with 'remote' - include trailing slash
    'api_endpoint' => 'https://api.example.com/',
];

