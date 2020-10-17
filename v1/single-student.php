<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset:UTF-8");

header("Access-Control-Allow-Method: POST");

include_once('../classes/student.php');
include_once('../config/database.php');

$db = new Database();
$connection = $db->connect();

$student = new Student($connection);



    if($_SERVER["REQUEST_METHOD"] === "POST"){


    $param = json_decode(file_get_contents("php://input"));

    if(!empty($param->id)){
        $student->id = $param->id;
        $student_data = $student->getSingleStudent();
        
        if(!empty($student_data)){
            http_response_code(200);

            echo json_encode(
                array(
                    "error" => false,
                    "student" => $student_data
                )
                );
        }else{
            http_response_code(404);
            echo json_encode(
                array(
                    "error" => true,
                    "message" => "Student not found"
                )
            );
        }
    }

}else{
    http_response_code(503);
    echo json_encode(
        array(
            "error" => true,
            "message" => "Access denied"
        )
        );
}
?>