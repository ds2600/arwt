<?php
date_default_timezone_set('America/New_York');
// Site Configuration
return [
    'callsign' => 'ARWT', // Your call sign
    'gmrs_callsign' => '', // Your GMRS call sign, if applicable. OPTIONAL.
    'base_url' => '', // Base URL of the web host, probably your domain.
    'uls_search' => false, // Enable or disable FCC ULS search.
    'uls_search_limit' => 5, // Limit the number of ULS searches per hour
    'redis_cache' => false, // Enable Redis Cache for FCC ULS searches
    'debug' => false, // Enable or disable debug mode
];

