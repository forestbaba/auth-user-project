<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type:application/json; charset: UTF-8");
    header('Access-Control-Allow-Methods: POST');

    include_once('../config/database.php');
    include_once('../classes/student.php');

    //create object for database;
    $db = new Database();
    $connection = $db->connect();

    $student = new Student($connection);

    if($_SERVER["REQUEST_METHOD"] === "POST"){

        $data = json_decode(file_get_contents('php://input'));
        // print_r($data); die;

        if(!empty($data->name) && !empty($data->email) && !empty($data->mobile_no)){
            $student->name = $data->name;
            $student->email = $data->email;
            $student->mobile_no = $data->mobile_no;
        
            if($student->create_data()){
                http_response_code(200);

                echo json_encode(
                    array(
                        "error"=> false,
                        "message" => "Student created"
                    )
                    );
            }else{

                http_response_code(400);
               echo json_encode(
                    array(
                        "error"=> true,
                        "message" => "Fail to create student"
                    )
                    );
            }
        }else{
            http_response_code(400);
              echo  json_encode(
                    array(
                        "error"=> true,
                        "message" => "All fields are required"
                    )
                    );
        }

       
        

    }else{
       http_response_code(401);
            echo    json_encode(
                    array(
                        "error"=> true,
                        "message" => "Access denied"
                    )
                    );
    }

?>