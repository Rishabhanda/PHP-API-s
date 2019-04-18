<?php 
    class Users{
        private $table = 'users';
        private $conn;
        
        //properties of the table 
        public $user_id;
        public $user_username;
        public $user_password;
        public $user_image;
        public $user_firstname;
        public $user_lastname;
        public $user_role;
        public $user_email;

        //constructor function
        public function __construct($db){
            $this->conn = $db;
        }

        //function for finding the user for LOGGING IN
        public function read_user(){
            $query = "SELECT * FROM {$this->table} WHERE {$this->table}.user_email = :email AND {$this->table}.user_password = :password";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email',$this->user_email);
            $stmt->bindParam(':password',$this->user_password);
            $stmt->execute();
            return $stmt;   
        }

        //function to check the email is already in db or not
        public function check_email_in_db(){
            $query = "SELECT {$this->table}.user_email FROM {$this->table} WHERE {$this->table}.user_email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $this->user_email);
            $stmt->execute();
            return $stmt;
        }

        //function for creating the user
        public function create_user(){
            $query = "INSERT INTO {$this->table} (user_username, user_firstname, user_lastname, user_password, user_image , user_email) VALUES ";
            $query .= " (:username, :firstname, :lastname, :password , :image,  :email)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $this->user_username);
            $stmt->bindParam(':firstname', $this->user_firstname);
            $stmt->bindParam(':lastname', $this->user_lastname);
            $stmt->bindParam(':password', $this->user_password);
            $stmt->bindParam(':image', $this->user_image);
            $stmt->bindParam(':email', $this->user_email);
            $stmt->execute();
            return $stmt;
        }

        //function to read all users
        public function read_all_users(){
            $query = "SELECT {$this->table}.user_id, {$this->table}.user_username, {$this->table}.user_firstname, {$this->table}.user_lastname, {$this->table}.user_email, {$this->table}.user_role, {$this->table}.user_image FROM {$this->table}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        //function to delete selected user from table
        public function delete_user(){
            $query = "DELETE FROM {$this->table} WHERE {$this->table}.user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id',$this->user_id);
            $stmt->execute();
            return $stmt;
        } 

        ///function to update the role of the user
        public function update_user_role(){
            $query = "UPDATE {$this->table} SET {$this->table}.user_role = :role WHERE {$this->table}.user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':role',$this->user_role);
            $stmt->bindParam(':user_id',$this->user_id);
            $stmt->execute();
            return $stmt;
        }

    }
?>