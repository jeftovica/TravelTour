<?php

require_once __DIR__ . '/../services/UserService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('user_service', new UserService());

Flight::route('POST /users', function(){

    $payload=Flight::request()->data->getData();

    if($payload['email']==""){
        Flight::halt(500, 'Email is missing');
    }
    if($payload['password']==""){
        Flight::halt(500, 'Password is missing');
    }

    $user = Flight::get('user_service')-> login($payload);
    if($user){
        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24) 
        ];

        $token = JWT::encode(
            $jwt_payload,
            JWT_SECRET,
            'HS256'
        );

        Flight::json(['message'=>'You have successfully Signed In', 'data'=> $token]);
    }else{
        Flight::halt(500, 'Credentials are not valid');
    }
});

Flight::route('POST /users/add', function(){

    $payload=Flight::request()->data->getData();

    if($payload['name']==""){
        Flight::halt(500, 'Name is missing');
    }
    if($payload['surname']==""){
        Flight::halt(500, 'Surname is missing');
    }
    if($payload['email']==""){
        Flight::halt(500, 'Email is missing');
    }
    if($payload['phone']==""){
        Flight::halt(500, 'Phone is missing');
    }
    if($payload['password']==""){
        Flight::halt(500, 'Password is missing');
    }

    $user = Flight::get('user_service')-> add_user($payload);
    Flight::json(['message'=>'You have successfully added user', 'data'=> $user]);
});

Flight::route('POST /logout', function() {
    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

        Flight::json([
            'jwt_decoded' => $decoded_token,
            'user' => $decoded_token->user
        ]);
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});

Flight::route('GET /whoAmI', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        Flight::json(['data'=> $decoded_token["user"]]);
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

});