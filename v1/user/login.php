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
    $data = json_decode(file_get_contents("php://input"));

     

    if(!empty($data->email) && !empty($data->password)){

        $userObject->email = $data->email;
        $userObject->password = $data->password;

        $userData  = $userObject->checkLogin(); 

       
        if(!empty($userData)){

            $name = $userData["name"];
            $email = $userData["email"];
            $password = $userData["password"];

            if(password_verify($data->password, $password)){
                
                $iss ="localhost";
                $iat =time();
                $nbf= $iat + 10;
                $exp = $iat + 30;
                $aud ="myusers";
                $user_arr_data= array(
                    "id"=> $userData["id"],
                    "name"=>$userData["name"],
                    "email"=> $userData["email"]
                );
                $payloadInfo =array(
                    "iss"=> $iss,
                    "iat"=> $iat,
                    "nbf"=> $nbf,
                    "exp"=> $exp,
                    "aud"=> $aud,
                    "data"=> $user_arr_data
                    );

                    $secretKey ="keyword199";

               $jwt = JWT::encode($payloadInfo, $secretKey);

                http_response_code(200);
                echo json_encode(
                    array(
                        "error"=> false,
                        "jwt" => $jwt,
                        "message" =>"Login Success"
                    )
                );
            }   else{
                http_response_code(400);
                echo json_encode(
                    array(
                        "error" => true,
                        "message" => "invalid credentials"
                    )
                    );
            }

        }else{
            http_response_code(404);
            echo json_encode(
                array(
                    "error" => true,
                    "message" => "User not found"
                )
                );
        }

    }else{
    http_response_code(400);
    echo json_encode(
        array(
            "error"=> true,
            "message" => "email and password is required"
        )
        );
    }
}

?>