<?php


class Users{

    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project;
    public $description;
    public $status;

    private $conn;
    private $users_table;
    private $project_table;


    public function __construct($db){
        $this->conn = $db;
        $this->users_table ="users";
        $this->project_table = "projects";

    }

    public function createUser(){
        $query = "INSERT INTO ".$this->users_table ." SET name=?, email =?, password = ?";

        if($obj = $this->conn->prepare($query)){
            $obj->bind_param("sss",$this->name, $this->email, $this->password);
           $obj->execute();

        }else{
            var_dump($this->conn->error);
        }
    }

    public function checkEmail(){
        $query = "SELECT * FROM ". $this->users_table." WHERE email = ?";
        $obj = $this->conn->prepare($query);
        $obj->bind_param("s", $this->email);
        
        if($obj->execute()){
             $data = $obj->get_result();
            return $data->fetch_assoc();
        }
        
        return array();
    }

    public function checkLogin(){
        $query = "SELECT * FROM ".$this->users_table . " WHERE email = ?";
        $obj = $this->conn->prepare($query);
        $obj->bind_param("s", $this->email);
        if($obj->execute()){

            $data = $obj->get_result();
            return $data->fetch_assoc();
        }
        return array();
    }

    public function createProject(){
        $query = "INSERT INTO ". $this->project_table ."  SET user_id = ?, name =?, description=?, status =?";

       if( $projectObj = $this->conn->prepare($query)){
           $user_id = htmlspecialchars(strip_tags($this->user_id));
           $project_name = htmlspecialchars(strip_tags($this->project));
           $description = htmlspecialchars(strip_tags($this->description));
           $status = htmlspecialchars(strip_tags($this->status));

           $projectObj -> bind_param("isss", $this->user_id, $this->project, $this->description, $this->status);
           $projectObj->execute();

           return $projectObj;

       }else{
           var_dump($this->conn->error);
       }
    }

    public function fetchAllProjects(){
        $query = "SELECT * FROM ".$this->project_table ." ORDER by id DESC";

        if($projectObject = $this->conn->prepare($query)){
            $projectObject->execute();
            return $projectObject->get_result();

        }else{
            echo var_dump($this->conn->error);
        }
    }

    public function fetchByUser(){
        $query = "SELECT * from ". $this->project_table ." WHERE user_id = ? ORDER by id DESC";

        if($projectObject = $this->conn->prepare($query)){
            $user_id = htmlspecialchars(strip_tags($this->user_id));

            $projectObject->bind_param("i", $this->user_id);
            $projectObject->execute();
            return $projectObject->get_result();
        }else{
            var_dump($this->conn->error);
        }
    }
}

?>