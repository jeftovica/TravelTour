<?php
require_once __DIR__ . '/rest/services/UserService.class.php';
$payload=$_REQUEST;
if($payload['email']==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Email is missing']));
}
if($payload['password']==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Password is missing']));
}

$user_service = new UserService();
$user = $user_service-> login($payload);
if($user){
    echo json_encode(['message'=>'You have successfully Signed In', 'data'=> $user]);
}else{
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Credentials are not valid']));
}

