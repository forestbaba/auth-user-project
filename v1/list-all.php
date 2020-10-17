<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset:UTF-8");
header("Access-Control-Allow-Method: GET");

include_once('../classes/student.php');
include_once('../config/database.php');

$db = new Database();
$connection = $db->connect();

$student = new Student($connection);

if($_SERVER["REQUEST_METHOD"] === "GET"){
    $data = $student->getAllData();

    if($data -> num_rows > 0){
        $students["records"] = array();

        while($row = $data -> fetch_assoc()){
            array_push($students["records"], array(
                "id" =>$row["id"],
                "name" =>$row["name"],
                "email" =>$row["email"],
                "mobile_no" =>$row["mobile_no"],
                "status" =>$row["status"],
                "created_at" =>date("Y-m-d",strtotime($row["created_at"]))
            ));

            http_response_code(200);
            echo json_encode(
                array(
                    "error" => false,
                    "records" => $students["records"]
                )
                );
        }
    }

}else{
    http_response_code(503);
    echo json_encode(
        array(
            "error" => true,
            "message" =>"Access denied"
        )
        );
}


?>