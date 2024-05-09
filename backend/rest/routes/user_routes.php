<?php

require_once __DIR__ . '/rest/services/UserService.class.php';

Flight::set('user_service', new UserService());

Flight::route('GET /users', function(){

$payload=Flight::request()->query;

if($payload['email']==""){
    header('HTTP/1.1 500 Bad Request');
    die(Flight::json(['error'=>'Email is missing']));
}
if($payload['password']==""){
    header('HTTP/1.1 500 Bad Request');
    die(Flight::json(['error'=>'Password is missing']));
}

$user = $user_serviceFlight::get('user_service')-> login($payload);
if($user){
    Flight::json(['message'=>'You have successfully Signed In', 'data'=> $user]);
}else{
    header('HTTP/1.1 500 Bad Request');
    die(Flight::json(['error'=>'Credentials are not valid']));
}
});
