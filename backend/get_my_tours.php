<?php
require_once __DIR__ . '/rest/services/TourService.class.php';
$payload=array();
parse_str($_SERVER['QUERY_STRING'],$payload);
if($payload["id"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Id is missing']));
}

$tour_service = new TourService();
$tours = $tour_service-> get_my_tours($payload['id']);
echo json_encode(['data'=> $tours]);

