<?php
require_once __DIR__ . '/rest/services/TourService.class.php';
$payload=json_decode(file_get_contents("php://input"),true);
if($payload["user_id"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'User id is missing']));
}
if($payload["tour_id"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Tour id is missing']));
}
$tour_service = new TourService();
$tour_service-> add_reservation($payload['tour_id'],$payload['user_id']);
echo json_encode(['message'=>'You have successfully added new reservation']);

