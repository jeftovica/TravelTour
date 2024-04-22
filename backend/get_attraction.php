<?php
require_once __DIR__ . '/rest/services/AttractionService.class.php';
$payload=array();
parse_str($_SERVER['QUERY_STRING'],$payload);
if($payload["id"]==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Id is missing']));
}

$attraction_service = new AttractionService();
$attraction = $attraction_service-> get_attraction($payload['id']);
echo json_encode(['data'=> $attraction]);

