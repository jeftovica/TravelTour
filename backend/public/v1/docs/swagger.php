<?php

require __DIR__ . '/../../../vendor/autoload.php';

if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1'){
    define('BASE_URL', 'http://localhost:80/TravelTour/backend/');
} else {
    define('BASE_URL', 'http://localhost:80/TravelTour/backend/');
}

error_reporting(E_ALL);

$openapi = \OpenApi\Generator::scan(['../../../rest/routes', './']);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
?>
