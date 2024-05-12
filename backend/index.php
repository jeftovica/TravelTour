<?php

require 'vendor/autoload.php';
require 'rest/routes/attraction_routes.php';
require 'rest/routes/tour_routes.php';
require 'rest/routes/user_routes.php';

define('BASE_URL', 'http://localhost:8018/TravelTour/backend/');

error_reporting(E_ALL);

$openapi = \OpenApi\Generator::scan(['/rest/routes', './']);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();


Flight::start();