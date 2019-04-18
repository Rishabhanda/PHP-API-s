<?php 
    class Categories{

        private $conn;
        private $table = 'categories';

        //properties
        public $cat_id;
        public $cat_title;

        //constructor
        public function __construct($dbh){
            $this->conn = $dbh;
        }

        //function for reading all categories
        public function read(){
            $query = "SELECT * FROM {$this->table}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }
        
        //function for reading single category
        public function read_one(){
            $query = "SELECT * FROM {$this->table} WHERE {$this->table}.cat_id = {$this->cat_id}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        //function for creating category
        public function create_category(){
            $query = "INSERT INTO {$this->table} (cat_title) VALUES (:cat_title)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cat_title',$this->cat_title);    
            $stmt->execute();
            return $stmt;
        }

        //function to delete category
        public function delete_category(){
            $query = "DELETE FROM {$this->table} WHERE {$this->table}.cat_id = (:cat_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cat_id', $this->cat_id);
            $stmt->execute();
            return $stmt;
           
        }

        //function to update category
        public function update_category(){
            $query = "UPDATE {$this->table} SET cat_title = :cat_title WHERE  {$this->table}.cat_id = :cat_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cat_title',$this->cat_title);
            $stmt->bindParam(':cat_id',$this->cat_id);
            $stmt->execute();
            return $stmt;
        }




    }
?>