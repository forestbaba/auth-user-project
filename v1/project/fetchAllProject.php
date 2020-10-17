<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: GET");
header("Content-TYpe: application/json; charset:UTF-8");

include_once('../../classes/users.php');
include_once('../../config/database.php');

$db = new Database();
$connection = $db->connect();
$userObject =new Users($connection);

if($_SERVER["REQUEST_METHOD"] === "GET"){

    $data = $userObject->fetchAllProjects();

    if($data -> num_rows > 0){

                $projects["allProjects"] = array();

    while($rows = $data-> fetch_assoc()){

        array_push($projects["allProjects"],array(
            "id"=>$rows["id"],
            "user"=> $rows["user_id"],
            "name" => $rows["name"],
            "description" =>  $rows["description"],
            "status" => $rows["status"],
            "created"=> $rows["created_at"],
        ));

    }

      http_response_code(200);
        echo json_encode(
            array(
                "error" => false,
                "projects"=> $projects["allProjects"]
            )
            );
    }

   

}else{
    http_response_code(404);
    echo json_encode(
        array(
            "error" => true,
            "message" => "Invalid request method"
        )
        );
}

?>