<?php
require_once __DIR__ . '/rest/services/TourService.class.php';

$tour_service = new TourService();
$tours = $tour_service-> get_all();
echo json_encode(['data'=> $tours]);

