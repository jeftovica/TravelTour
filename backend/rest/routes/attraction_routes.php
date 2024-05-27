<?php

require_once __DIR__ . '/../services/AttractionService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('attraction_service', new AttractionService());

/**
     * @OA\Get(
     *      path="/attractions",
     *      tags={"attractions"},
     *      summary="Get all attractions",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Array of all attractions in the databases"
     *      )
     * )
     */
Flight::route('GET /attractions', function(){
    $attractions = Flight::get('attraction_service')-> get_all();
    Flight::json(['data'=> $attractions]);
});

/**
 * @OA\Get(
 *      path="/attractions/one/{attraction_id}",
 *      tags={"attractions"},
 *      summary="Get attraction by id",
 *      security={
 *          {"ApiKey": {}}   
 *      },
 *      @OA\Parameter(
 *         name="attraction_id",
 *         in="path",
 *         description="ID of the attraction",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Attraction data, or false if attraction does not exist"
 *      )
 * )
 */
Flight::route('GET /attractions/one/@attraction_id', function($attraction_id){
    $attraction = Flight::get('attraction_service')-> get_attraction($attraction_id);
    Flight::json(['data'=> $attraction]);

});

/**
     * @OA\Post(
     *      path="/attractions/add",
     *      tags={"attractions"},
     *      summary="Add attraction data to the database",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Attraction data, or exception if attraction is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="Attraction data payload",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *              @OA\Property(property="name", type="string", example="Attraction Name", description="Attraction name"),
     *              @OA\Property(property="description", type="string", example="Description", description="Description of attraction"),
     *              @OA\Property(property="image", type="string",format="binary", description="Image of attraction")
     *          )
     *      )
     * )
     * )
     */
Flight::route('POST /attractions/add', function(){

    if(Flight::get("user")->role!="admin"){
        Flight::halt(403, "Permission denied.");
    }

    $payload=Flight::request()->data->getData();
    $request = Flight::request();
    $file = $request->files['image'];

    $targetDirectory = "uploads/";

    $targetFile = "../" . $targetDirectory . uniqid("attraction-",false) . $file["name"];
    
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
    
    $attraction = Flight::get('attraction_service')-> add_attraction($payload);
    Flight::json(['message'=>'You have successfully', 'data'=> $attraction]);
});

/**
 * @OA\Delete(
 *      path="/attractions/delete/{attraction_id}",
 *      tags={"attractions"},
 *      summary="Delete attraction by id",
 *      security={
 *          {"ApiKey": {}}   
 *      },
 *      @OA\Parameter(
 *         name="attraction_id",
 *         in="path",
 *         description="ID of the attraction",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Deleted attraction data or 500 status code exception otherwise"
 *      )
 * )
 */
Flight::route('DELETE /attractions/delete/@attraction_id', function($attraction_id){

    if(Flight::get("user")->role!="admin"){
        Flight::halt(403, "Permission denied.");
    }

    Flight::get('attraction_service')-> delete_attraction($attraction_id);
    Flight::json(['message'=> "Attraction deleted successfully"]);
});

/**
 * @OA\Post(
 *      path="/attractions/edit",
 *      tags={"attractions"},
 *      summary="Edit existing attraction data in the database",
 *      security={
 *          {"ApiKey": {}}   
 *      },
 *      @OA\Response(
 *           response=200,
 *           description="Attraction data updated successfully"
 *      ),
 *      @OA\Response(
 *           response=404,
 *           description="Attraction not found"
 *      ),
 *      @OA\RequestBody(
 *          description="Updated attraction data payload",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *              @OA\Property(property="id", type="string", example="1", description="Attraction ID"),
 *              @OA\Property(property="name", type="string", example="Updated Attraction Name", description="Updated attraction name"),
 *              @OA\Property(property="description", type="string", example="Updated Description", description="Updated description of attraction"),
 *              @OA\Property(property="image", type="string",format="binary", description="Updated image of attraction")
 *              )
 *          )
 *      )
 * )
 */

Flight::route('POST /attractions/edit', function(){ 

    if(Flight::get("user")->role!="admin"){
        Flight::halt(403, "Permission denied.");
    }

    $payload=Flight::request()->data->getData();
    $request = Flight::request();
    $file = $request->files['image'];
    if ($file){
        $targetDirectory = "uploads/";
        $targetFile = "../" . $targetDirectory . uniqid("attraction-",false) . $file["name"];
        move_uploaded_file($file["tmp_name"], $targetFile);
        $payload['image']=$targetFile;

    }

    
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

