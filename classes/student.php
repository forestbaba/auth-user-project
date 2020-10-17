<?php


    class Student{
        public $name;
        public $email;
        public $mobile_no;
        public $id;


        private $conn;
        private $table_name;


        public function __construct($db){
            $this->conn = $db;
            $this->table_name ="students";
        }
        public function create_data(){
            $query ="INSERT INTO " . $this->table_name . " SET name = ?, email = ?, mobile_no = ? ";


            if( $obj = $this->conn->prepare($query)){
                //Sanitize the input variables which means,  Remove extrac characters 
            //like some spacial symbols as well as some tags available in input values

            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->mobile_no = htmlspecialchars(strip_tags($this->mobile_no));

            //Binding parameters with prepare statement
            $obj->bind_param("sss",$this->name, $this->email, $this->mobile_no);
            
            $obj->execute();
            
            }else{
                var_dump($this->conn->error);
            }
           

            
        }

        public function getAllData(){
            $query = "SELECT * FROM ".$this->table_name;

             if( $obj = $this->conn->prepare($query)){
             $obj->execute();
             return $obj->get_result();

            }else{
                var_dump($this->conn->error);

            }
        }

        public function getSingleStudent(){
            $query = "SELECT * FROM " . $this->table_name. " WHERE id = ?";
            
            if($obj = $this->conn->prepare($query)){

                $obj->bind_param("i", $this->id);
                $obj->execute();
                
                $data = $obj->get_result();
                return $data-> fetch_assoc();

            }else{
                var_dump($this->conn->error);
            }

        }

        public function updateStudent(){
            $query = "UPDATE ". $this->table_name." SET email =?, name =?, mobile_no = ? WHERE id =?";


            if( $obj = $this->conn->prepare($query)){

            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->mobile_no = htmlspecialchars(strip_tags($this->mobile_no));
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Binding parameters with prepare statement
            $obj->bind_param("sssi",$this->name, $this->email, $this->mobile_no, $this->id);
            
            $obj->execute();
            
            }else{
                var_dump($this->conn->error);
            }
           
        }

        public function deleteStudent(){
            $query = "DELETE FROM ". $this->table_name ." WHERE id = ?";

            if($obj = $this->conn->prepare($query)){
                $this->id = htmlspecialchars(strip_tags($this->id));
                $obj->bind_param("i", $this->id);
                $obj->execute();
            }else{
                var_dump($this->conn->error);
            }
        }
    }
?>