<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRICATED));

// DB setup

define('DB_NAME', 'travel_tour');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASSWORD', 'angela01180');
define('DB_HOST', '127.0.0.1');