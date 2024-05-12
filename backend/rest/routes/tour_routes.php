<?php

require_once __DIR__ . '/../services/TourService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('tour_service', new TourService());

/**
     * @OA\Get(
     *      path="/tours",
     *      tags={"tours"},
     *      summary="Get all tours",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Array of all tours in the databases"
     *      )
     * )
     */
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

/**
     * @OA\Post(
     *      path="/tours/reservation",
     *      tags={"tours"},
     *      summary="Add reservation data to the database",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Reservation data, or exception if reservation is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="Reservation data payload",
     *          @OA\JsonContent(
     *              required={"tour_id"},
     *              @OA\Property(property="id", type="string", example="1", description="Tour ID"),
     *          )
     *      )
     * )
     */
Flight::route('POST /tours/reservation', function(){
    $id=-1;
    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token->user->role!="user"){
            Flight::halt(403, "Permission denied.");
        }
        $id=$decoded_token->user->id;
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $payload=Flight::request()->data->getData();

    if($payload["tour_id"]==""){
        Flight::halt(500, 'Tour id is missing');
    }
    Flight::get('tour_service')-> add_reservation($payload['tour_id'],$id);
    Flight::json(['message'=>'You have successfully added new reservation']);
});


/**
     * @OA\Post(
     *      path="/tours/add",
     *      tags={"tours"},
     *      summary="Add tour data to the database",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Tour data, or exception if tour is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="Tour data payload",
     *          @OA\JsonContent(
     *              required={"name","description","image", "startDate", "endDate", "price"},
     *              @OA\Property(property="id", type="string", example="1", description="Tour ID"),
     *              @OA\Property(property="name", type="string", example="Tour 1", description="Tour name"),
     *              @OA\Property(property="description", type="string", example="Description 1", description="Description of tour"),
     *              @OA\Property(property="image", type="string", example="https://example.com/updated_image.jpg", description="Image of tour"),
     *              @OA\Property(property="startDate", type="string", example="2024-04-04", description="Tour start date"),
     *              @OA\Property(property="endDate", type="string", example="2024-05-04", description="Tour end date"),
     *              @OA\Property(property="price", type="string", example="123", description="Price of tour"),
     *          )
     *      )
     * )
     */
Flight::route('POST /tours/add', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token->user->role!="admin"){
            Flight::halt(403, "Permission denied.");
        }
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
    
    $payload=Flight::request()->data->getData();

    $request = Flight::request();
    $file = $request->files['image'];

    $targetDirectory = "uploads/";

    $targetFile = "../" . $targetDirectory . uniqid("tour-",false) . $file["name"];
    
    move_uploaded_file($file["tmp_name"], $targetFile);

    $payload['image']=$targetFile;

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
    $payload['attractions']=json_decode($payload['attractions'],true);
    
    $tour = Flight::get('tour_service')-> add_tour($payload);
    Flight::json(['message'=>'You have successfully added new tour', 'data'=> $tour]);
});

/**
 * @OA\Delete(
 *      path="/tours/delete/{tour_id}",
 *      tags={"tours"},
 *      summary="Delete tour by id",
 *      security={
 *          {"ApiKey": {}}   
 *      },
 *      @OA\Parameter(
 *         name="tour_id",
 *         in="path",
 *         description="ID of the tour",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Deleted tour data or 500 status code exception otherwise"
 *      )
 * )
 */
Flight::route('DELETE /tours/delete/@tour_id', function($tour_id){   
    
    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token->user->role!="admin"){
            Flight::halt(403, "Permission denied.");
        }
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $tour = Flight::get('tour_service')-> delete_tour($tour_id);
    Flight::json(['data'=> $tour]);
});

/**
 * @OA\Post(
 *      path="/tours/edit",
 *      tags={"tours"},
 *      summary="Edit existing tour data in the database",
 *      security={
 *          {"ApiKey": {}}   
 *      },
 *      @OA\Response(
 *           response=200,
 *           description="Tour data updated successfully"
 *      ),
 *      @OA\Response(
 *           response=404,
 *           description="Tour not found"
 *      ),
 *      @OA\RequestBody(
 *          description="Updated attraction data payload",
 *          @OA\JsonContent(
 *              @OA\Property(property="id", type="string", example="1", description="Tour ID"),
 *              @OA\Property(property="name", type="string", example="Tour 1", description="Tour name"),
 *              @OA\Property(property="description", type="string", example="Description 1", description="Description of tour"),
 *              @OA\Property(property="image", type="string", example="https://example.com/updated_image.jpg", description="Image of tour"),
 *              @OA\Property(property="startDate", type="string", example="2024-04-04", description="Tour start date"),
 *              @OA\Property(property="endDate", type="string", example="2024-05-04", description="Tour end date"),
 *              @OA\Property(property="price", type="string", example="123", description="Price of tour"),
 *          )
 *      )
 * )
 */
Flight::route('POST /tours/edit', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token->user->role!="admin"){
            Flight::halt(403, "Permission denied.");
        }
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $payload=Flight::request()->data->getData();
    $request = Flight::request();
    $file = $request->files['image'];
    if ($file){
        $targetDirectory = "uploads/";
        $targetFile = "../" . $targetDirectory . uniqid("tour-",false) . $file["name"];
        move_uploaded_file($file["tmp_name"], $targetFile);
        $payload['image']=$targetFile;

    }
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
    $payload['attractions']=json_decode($payload['attractions'],true);
    
    
    $tour = Flight::get('tour_service')-> edit_tour($payload);
    Flight::json(['message'=>'You have successfully edited', 'data'=> $tour]);
    
});

/**
     * @OA\Get(
     *      path="/tours/my",
     *      tags={"tours"},
     *      summary="Get users tours",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Tours data, or false if tour does not exist"
     *      ),
     * )
     */
Flight::route('GET /tours/my', function(){
    $user_id=-1;
    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        if($decoded_token->user->role!="user"){
            Flight::halt(403, "Permission denied.");
        }
        $user_id=$decoded_token->user->id;
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

    $tours = Flight::get('tour_service')-> get_my_tours($user_id);
    Flight::json(['data'=> $tours]);
});

/**
     * @OA\Get(
     *      path="/tours/popular",
     *      tags={"tours"},
     *      summary="Get popular tours",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Tours data, or false if tour does not exist"
     *      ),
     * )
     */
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

/**
 * @OA\Get(
 *      path="/tours/one/{tour_id}",
 *      tags={"tours"},
 *      summary="Get tour",
 *      security={
 *          {"ApiKey": {}}   
 *      },
 *      @OA\Parameter(
 *         name="tour_id",
 *         in="path",
 *         description="ID of the tour",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Tour data, or false if tour does not exist"
 *      )
 * )
 */
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
