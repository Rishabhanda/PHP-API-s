<?php 
    class Posts{
        private $conn;
        private $table = 'posts';

        //properties 
        public $post_id;
        public $post_category_id;
        public $post_title;
        public $post_author;
        public $post_date;
        public $post_image;
        public $post_content;
        public $post_tags;
        public $post_comment_count;
        public $post_status;
        

        //constructor
        public function __construct($db){
            $this->conn = $db;
        }

        //function to read all posts
        public function read_post(){
            $query = "SELECT {$this->table}.* , categories.cat_title FROM {$this->table} 
                        LEFT JOIN categories ON {$this->table}.post_category_id = categories.cat_id 
                        ORDER BY {$this->table}.post_id ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        //function to read single post
        public function read_single(){
            $query = "SELECT {$this->table}.* , categories.cat_title FROM {$this->table} 
                        LEFT JOIN categories ON {$this->table}.post_category_id = categories.cat_id 
                        WHERE {$this->table}.post_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id',$this->post_id);
            $stmt->execute();
            return $stmt;
    
        }

        //function to delete post
        public function delete_post(){
            $query = "DELETE FROM {$this->table} WHERE {$this->table}.post_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id',$this->post_id);
            $stmt->execute();
            return $stmt;
        } 

        //function to  create post
        public function create_post(){
            $query = "INSERT INTO {$this->table} (post_category_id, post_title, post_author , post_date , post_image, post_content, post_tags, post_comment_count, post_status) VALUES
                                                 (:cat_id , :title , :author , NOW() , :post_image , :content , :tags  , :comment_count , :post_status )";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cat_id', $this->post_category_id);
            $stmt->bindParam(':title',  $this->post_title);
            $stmt->bindParam(':author',  $this->post_author);
            $stmt->bindParam(':post_image',  $this->post_image);
            $stmt->bindParam(':content',  $this->post_content);     
            $stmt->bindParam(':tags',  $this->post_tags);
            $stmt->bindParam(':comment_count',  $this->post_comment_count);
            $stmt->bindParam(':post_status',  $this->post_status);
            $stmt->execute();
            return $stmt;
        }

        //function to uppdate post
        public function update_post(){
            $query = "UPDATE {$this->table} SET {$this->table}.post_category_id = :category_id, {$this->table}.post_title = :title, {$this->table}.post_author = :author";
            $query .= " , {$this->table}.post_date = NOW() , {$this->table}.post_content = :content, {$this->table}.post_tags = :tags";

            if(!empty($this->post_image)){
                $query .= " , {$this->table}.post_image = :image ";
            }

            $query .= " , {$this->table}.post_status = :status, {$this->table}.post_comment_count = :comment_count  WHERE {$this->table}.post_id = :post_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':post_id',$this->post_id);
            $stmt->bindParam(':category_id',$this->post_category_id);
            $stmt->bindParam(':title',$this->post_title);
            $stmt->bindParam(':author',$this->post_author);

            if(!empty($this->post_image)){
                $stmt->bindParam(':image',$this->post_image);
            }

            $stmt->bindParam(':content',$this->post_content);
            $stmt->bindParam(':comment_count',$this->post_comment_count);
            $stmt->bindParam(':tags',$this->post_tags);
            $stmt->bindParam(':status',$this->post_status);
            $stmt->execute();
            return $stmt;
        }
    }
?>