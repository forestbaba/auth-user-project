<?php

   require '../../vendor/autoload.php';
    use \Firebase\JWT\JWT;


    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Method: GET");
    header("Content-Type: application/json; charset:UTF-8");

    include_once('../../classes/users.php');
    include_once('../../config/database.php');

    $db = new Database();
    $connection = $db->connect();
    $userObject = new Users($connection);




if($_SERVER["REQUEST_METHOD"] === "GET"){

    // $uid = $_GET["uid"];


    // echo $uid;

    $authTokens = getallheaders();
    $jwt = $authTokens["Authorization"];

            if(!isset($jwt) || $jwt == null){
                
                http_response_code(401);
                echo json_encode(
                    array(
                        "error" => true,
                        "message" => "Auth token is required"
                    )
                    );
            }else{

                try {
                    
                
                $secretKey ="keyword199";
                $decodedData = JWT::decode($jwt,$secretKey, array("HS512"));


                $userObject->user_id = $decodedData->data->id;
                $project = $userObject->fetchByUser();

                if($project-> num_rows > 0 ){
                    $userProjects["projects"] = array();

                    while($data = $project->fetch_assoc()){
                        array_push($userProjects["projects"],array(
                            "id"            => $data["id"],
                            "name"          => $data["name"],
                            "description"   => $data["description"],
                            "status"        => $data["status"],
                            "created_at"    => $data["created_at"]
                        ));

                    }
                    http_response_code(200);
                    echo json_encode( array(
                        "error"=> false,
                        "projects"=>$userProjects["projects"]
                    ));
                }else{
                    http_response_code(200);
                    echo json_encode(array(
                        "error"=>false,
                        "project" => array()
                    ));
                }
                } catch (Exception $ex) {
                    echo json_encode(
                        array(
                            "error" => true,
                             "message" =>  $ex->getMessage()
                        )
                        );
                }


    }

    
}else{
    http_response_code(404);
}

?>