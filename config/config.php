<?php
// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// Database Helper Constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'makeup_shop');

// App Constants
define('BASE_URL', 'http://localhost/web-beauty');
define('APP_NAME', 'Lumière Beauty');

// Session Security Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
// Uncomment for HTTPS: ini_set('session.cookie_secure', 1);

// Start Session
session_start();
