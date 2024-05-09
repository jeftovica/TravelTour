<?php
require_once __DIR__ . '/rest/services/AttractionService.class.php';
$payload=json_decode(file_get_contents("php://input"),true);
if($payload["description"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Description is missing']));
}
if($payload["name"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Name is missing']));
}
if($payload["id"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Id is missing']));
}
$attraction_service = new AttractionService();
$attraction = $attraction_service-> edit_attraction($payload);
echo json_encode(['message'=>'You have successfully edited some attraction', 'data'=> $attraction]);

