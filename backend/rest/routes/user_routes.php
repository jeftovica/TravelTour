<?php

require_once __DIR__ . '/../services/UserService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('user_service', new UserService());
/**
 * @OA\Info(
 *   title="API",
 *   description="Web programming API",
 *   version="1.0",
 *   @OA\Contact(
 *     email="becir.isakovic@ibu.edu.ba",
 *     name="Becir Isakovic"
 *   )
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authentication"
 * )
 */
/**
     * @OA\Post(
     *      path="/users",
     *      tags={"users"},
     *      summary="Login to system using email and password",
     *      @OA\Response(
     *           response=200,
     *           description="User data and JWT"
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="emaill", type="string", example="example@example.com", description="User email address"),
     *              @OA\Property(property="password", type="string", example="some_password", description="User password")
     *          )
     *      )
     * )
     */
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

/**
     * @OA\Post(
     *      path="users/add",
     *      tags={"users"},
     *      summary="Add user data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="User data, or exception if user is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="User data payload",
     *          @OA\JsonContent(
     *              required={"name","surname","phone", "email", "password"},
     *              @OA\Property(property="id", type="string", example="1", description="User ID"),
     *              @OA\Property(property="name", type="string", example="User 1", description="Users name"),
     *              @OA\Property(property="surname", type="string", example="Surname 1", description="Users surname"),
     *              @OA\Property(property="phone", type="string", example="+38765432789", description="Users number"),
     *              @OA\Property(property="email", type="string", example="example@example.com", description="Users email")
     *              @OA\Property(property="password", type="string", example="some_password", description="Users password"),
     *          )
     *      )
     * )
     */
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


/**
     * @OA\Post(
     *      path="/logout",
     *      tags={"logout"},
     *      summary="Logout from the system",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Success response or exception if unable to verify jwt token"
     *      ),
     * )
     */
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

/**
     * @OA\Get(
     *      path="/whoAmI",
     *      tags={"whoAmI"},
     *      summary="Get user role",
     *      @OA\Response(
     *           response=200,
     *           description="User role"
     *      )
     * )
     */
Flight::route('GET /whoAmI', function(){

    try {
        $token = Flight::request()->getHeader("Authentication");
        if(!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token=JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        Flight::json(['data'=> $decoded_token->user]);
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }

});