<?php

require '../../vendor/autoload.php';
use \Firebase\JWT\JWT;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: POST");
header("Content-Type:application/json; charset:UTF-8");



    include_once('../../config/database.php');
    include_once('../../classes/users.php');

    //create object for database;
    $db = new Database();
    $connection = $db->connect();
    $userObject = new Users($connection);

    if($_SERVER["REQUEST_METHOD"] ==="POST"){
        $data = json_decode(file_get_contents("php://input"));

     
        if(!empty($data->project) && !empty($data->description) && !empty($data->status)){
            
            // if(!isset($headers["Authorization"])){
            //         echo "token is required";
            //     }
            $headers= getallheaders();

             $jwt = $headers["Authorization"];

             if(!isset($jwt) || $jwt == null){
                 
                 echo json_encode(
                     array(
                         "error" => true,
                         "message" => "Token is required"
                     )
                     );
             }else{

                    try {
                    
                    

                    $secretKey ="keyword199";

                    $decoded_data = JWT::decode($jwt, $secretKey, array("HS512"));

                    $userObject->user_id = $decoded_data->data->id;
                    $userObject->project = $data->project;
                    $userObject->description = $data->description;
                    $userObject->status = $data->status;

                    if($userObject->createProject()){
                        http_response_code(200);
                        echo json_encode(
                            array(
                                "error"=> false,
                                "message" => "Project created"
                            )
                            );
                    }

                    } catch (Exception $ex) {
                        
                        http_response_code(500);
                        echo json_encode(
                            array(
                                "error" => true,
                                "message" => $ex->getMessage()
                            )
                            );
                    }
             }
            

        }else{
            http_response_code(404);
            echo json_encode(
                array(
                    "error" => true,
                    "message" => "All data are required"
                )
                );
        }
    }




?>