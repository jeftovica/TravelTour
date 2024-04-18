<?php
require_once __DIR__ . '/rest/services/UserService.class.php';
$payload=$_REQUEST;
if($payload['name']==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Name is missing']));
}
if($payload['surname']==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Surname is missing']));
}
if($payload['email']==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Email is missing']));
}
if($payload['phone_number']==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Phone number is missing']));
}
if($payload['is_admin']==null){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Role is missing']));
}
if($payload['password']==""){
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error'=>'Password is missing']));
}
//TO_DO ostali podaci iz register
$user_service = new UserService();
$user = $user_service-> add_user($payload);
echo json_encode(['message'=>'You have successfully added user', 'data'=> $user]);

