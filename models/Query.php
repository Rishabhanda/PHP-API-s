<?php 
    class Query{
        private $table = 'users';
        private $conn;

        //property to fetch the user from firstname
        public $firstname;

        public function __construct($db){
            $this->conn = $db;
        }

        public function finding_user(){
            $query = "SELECT * FROM {$this->table} WHERE {$this->table}.user_firstname = :firstname";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':firstname',$this->firstname);
            $stmt->execute();
            return $stmt;
        }
    }




?>