<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

 include_once('../classes/student.php');
 include_once('../config/database.php');

 $db = new Database();


 $connection = $db->connect();
  $student = new Student($connection);


  
 if($_SERVER["REQUEST_METHOD"] === "GET"){
    
        $student_id = isset($_GET["id"]) ? intval($_GET["id"]) : "";

        if(!empty($student_id)){
            $student->id = $student_id;
            $student_data = $student->deleteStudent();

             http_response_code(200);

                echo json_encode(
                    array(
                        "error" => false,
                        "message" => "student deleted successfully"
                    )
                );
            
            // if($student->deleteStudent()){
               
            // }else{
            //     http_response_code(404);
            //     echo json_encode(
            //         array(
            //             "error" => true,
            //             "message" => "Student not found"
            //         )
            //     );
            // }
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