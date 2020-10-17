<?php
header("Access-Allow-Control-Origin: *");
header("Access-Allow-Control-Method: POST");
header("Content-Type: application/json; charset:UTF-8");

include_once('../../config/database.php');
include_once('../../classes/users.php');

$db = new Database();

$connection = $db->connect();

$userObject = new Users($connection);

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->name) && !empty($data->email) && !empty($data->password)){

        $userObject->name = $data->name;
        $userObject->email = $data->email;
        $userObject->password = password_hash($data->password,PASSWORD_DEFAULT);
        $emailData =$userObject -> checkEmail();

        if(!empty($emailData)){

            http_response_code(400);
            echo json_encode(
                array(
                    "error" => true,
                    "message" =>"user already exist"
                )
                );

        }else{

             $userObject->createUser();

        echo json_encode(
            array(
                "error" => false,
                "message" => "user created"
            )
            );
        }

       

    }else{
        http_response_code(401);
        echo json_encode(
            array(
                "error"=>  true,
                "message" => 'All fields are required'
            )
            );
    }

}else{
    http_response_code(503);
    echo json_encode(
        array(
            "error"=> true,
            "message" => "Access denied"
        )
        );
}

?>