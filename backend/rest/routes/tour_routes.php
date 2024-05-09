<?php

require_once __DIR__ . '/rest/services/TourService.class.php';

Flight::set('tour_service', new TourService());

Flight::route('GET /tours', function(){
    /*get all tours
    
    $tour_service = new TourService();
    $tours = $tour_service-> get_all();
    echo json_encode(['data'=> $tours]);*/

    $tours = Flight::get('tour_service')-> get_all();
    Flight::json(['data'=> $tours]);
});
Flight::route('GET /', function(){
    echo('HElloo');
});
