<?php


header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Method: POST");
header("Content-Type: application/json; charset=UTF-8");

require '../../vendor/autoload.php';
use \Firebase\JWT\JWT;

include_once('../../config/database.php');
include_once('../../classes/users.php');

$db = new Database();
$connection = $db->connect();
$userObject = new Users($connection);

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $all_headers = getallheaders();
    $data->jwt = $all_headers["Authorization"];


    if(!empty($data->jwt)){
        try {
             $secretKey ="keyword199";

             $decoded_data = JWT::decode($data->jwt, $secretKey, array("HS512"));
             //NOTE: Php jwt default comes with HS256, i changed it to HS512 in encode 
             //function of PHP module 

             http_response_code(200);
             echo json_encode(array(
                 "error" => false,
                 "message"=> "token decoded",
                 "user_id"=>$decoded_data->data->id,
                 "user_data" => $decoded_data
             ));

        } catch (Exception $ex) {
          http_response_code(500);

        echo json_encode(
            array(
                "error"=> true,
                "message" => $ex->getMessage()
            )
            );
        }
        
    }else{
        
        http_response_code(401);
        echo json_encode(
            array(
                "error"=> true,
                "message" => "Token required"
            )
            );
    }
}

?>