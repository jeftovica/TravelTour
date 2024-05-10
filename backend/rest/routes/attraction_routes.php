<?php

require_once __DIR__ . '/../services/AttractionService.class.php';

Flight::set('attraction_service', new AttractionService());

Flight::route('GET /attractions', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $attractions = Flight::get('attraction_service')-> get_all();
    Flight::json(['data'=> $attractions]);
});

Flight::route('GET /attractions/one/@attraction_id', function($attraction_id){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $attraction = Flight::get('attraction_service')-> get_attraction($attraction_id);
    Flight::json(['data'=> $attraction]);

});

Flight::route('POST /attractions/add', function(){

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
    
    $attraction = Flight::get('attraction_service')-> add_attraction($payload);
    Flight::json(['message'=>'You have successfully', 'data'=> $attraction]);
});

Flight::route('DELETE /attractions/delete/@attraction_id', function($attraction_id){

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

    Flight::get('attraction_service')-> delete_attraction($attraction_id);
    Flight::json(['message'=> "Attraction deleted successfully"]);
});

Flight::route('PUT /attracitons/edit', function(){ 

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
    if($payload["id"]==""){
        Flight::halt(500, 'ID is missing');
    }

    $attraction = Flight::get('attraction_service')-> edit_attraction($payload);
    Flight::json(['message'=>'You have successfully edited some attraction', 'data'=> $attraction]);
});

