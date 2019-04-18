<?php 
    class Database{
        private $dsn = 'mysql:host=localhost;dbname=ngphpcms';
        private $db_name = 'root';
        private $db_password = '';
        
        public function connection(){

            try{
                //connection with the database
                $dbh = new PDO( $this->dsn , $this->db_name , $this->db_password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $dbh;
            } catch(PDOException $e){

                echo 'Error '. $e->getMessage();
                

            }
        }
        
    }

?>