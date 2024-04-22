<?php
require_once __DIR__ . '/rest/services/TourService.class.php';
$payload=json_decode(file_get_contents("php://input"),true);
if($payload["description"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Description is missing']));
}
if($payload["name"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Name is missing']));
}
if($payload['image']==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Image is missing']));
}
if($payload['startDate']==null){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Start Date is missing']));
}
if($payload['endDate']==null){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'End Date is missing']));
}
if($payload['price']==null){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'price is missing']));
}

$tour_service = new TourService();
$tour = $tour_service-> add_tour($payload);
echo json_encode(['message'=>'You have successfully added new tour', 'data'=> $tour]);

