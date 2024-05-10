<?php

require_once __DIR__ . '/../services/TourService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('tour_service', new TourService());

Flight::route('GET /tours', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $tours = Flight::get('tour_service')-> get_all();
    Flight::json(['data'=> $tours]);
});

Flight::route('POST /tours/reservation', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token["user"]["role"]!="user"){
            Flight::halt(403, "Permission denied.");
        }
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $payload=Flight::request()->data->getData();

    if($payload["user_id"]==""){
        Flight::halt(500, 'User id is missing');
    }
    if($payload["tour_id"]==""){
        Flight::halt(500, 'Tour id is missing');
    }
    Flight::get('tour_service')-> add_reservation($payload['tour_id'],$payload['user_id']);
    Flight::json(['message'=>'You have successfully added new reservation']);
});

Flight::route('POST /tours/add', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token["user"]["role"]!="admin"){
            Flight::halt(403, "Permission denied.");
        }
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
    
    $payload=Flight::request()->data->getData();

    if($payload["description"]==""){
        Flight::halt(500, 'Description is missing');
    }
    if($payload["name"]==""){
        Flight::halt(500, 'Name is missing');
    }
    if($payload['image']==""){
        Flight::halt(500, 'Image is missing');
    }
    if($payload['startDate']==null){
        Flight::halt(500, 'Start date is missing');
    }
    if($payload['endDate']==null){
        Flight::halt(500, 'End date is missing');
    }
    if($payload['price']==null){
        Flight::halt(500, 'Price is missing');
    }
    
    $tour = Flight::get('tour_service')-> add_tour($payload);
    Flight::json(['message'=>'You have successfully added new tour', 'data'=> $tour]);
});

Flight::route('DELETE /tours/delete/@tour_id', function($tour_id){   
    
    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token["user"]["role"]!="admin"){
            Flight::halt(403, "Permission denied.");
        }
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $tour = Flight::get('tour_service')-> delete_tour($tour_id);
    Flight::json(['data'=> $tour]);
});

Flight::route('PUT /tours/edit', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token["user"]["role"]!="admin"){
            Flight::halt(403, "Permission denied.");
        }
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $payload=Flight::request()->data->getData();
    if($payload["id"]==""){
        Flight::halt(500, 'ID is missing');
    }
    if($payload["description"]==""){
        Flight::halt(500, 'Description is missing');
    }
    if($payload["name"]==""){
        Flight::halt(500, 'Name is missing');
    }
    if($payload['startDate']==null){
        Flight::halt(500, 'Start date is missing');
    }
    if($payload['endDate']==null){
        Flight::halt(500, 'End date is missing');
    }
    if($payload['price']==null){
        Flight::halt(500, 'Price is missing');
    }
    
    $tour = Flight::get('tour_service')-> edit_tour($payload);
    Flight::json(['message'=>'You have successfully edited', 'data'=> $tour]);
    
});

Flight::route('GET /tours/my', function(){
    $user_id=-1;
    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token["user"]["role"]!="user"){
            Flight::halt(403, "Permission denied.");
        }
        $user_id=$decoded_token["user"]["id"];
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $tours = Flight::get('tour_service')-> get_my_tours($user_id);
    Flight::json(['data'=> $tours]);
});

Flight::route('GET /tours/popular', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $tours = Flight::get('tour_service')-> get_popular_tours();
    Flight::json(['data'=> $tours]);
});

Flight::route('GET /tours/one/@tour_id', function($tour_id){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $tour = Flight::get('tour_service')-> get_tour($tour_id);
    Flight::json(['data'=> $tour]);
});
