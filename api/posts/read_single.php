<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');


    include '../../includes/Db.php';
    include '../../models/Posts.php';

    $db_connect = new Database();
    $db_connection = $db_connect->connection();
    $read_single_post = new Posts($db_connection);

    if(isset($_GET['p_id'])){
        $read_single_post->post_id = filter_var($_GET['p_id'] , FILTER_SANITIZE_NUMBER_INT);
        $stmt = $read_single_post->read_single();
        $num_of_selected_rows = $stmt->rowCount();
        if($num_of_selected_rows){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $read_single_post->post_id =  $row['post_id'];
                $read_single_post->post_category_id =  $row['post_category_id'];
                $read_single_post->post_title =  $row['post_title'];
                $read_single_post->post_author =  $row['post_author'];
                $read_single_post->post_date =  $row['post_date'];
                $read_single_post->post_image =  $row['post_image'];
                $read_single_post->post_content =  $row['post_content'];
                $read_single_post->post_tags =  $row['post_tags'];
                $read_single_post->post_status =  $row['post_status'];
                $read_single_post->post_comment_count =  $row['post_comment_count'];
                $post_read = [
                    "id"=>$read_single_post->post_id,
                    "cat_name"=>$row['cat_title'],
                    "cat_id"=>$read_single_post->post_category_id,
                    "title"=>$read_single_post->post_title,
                    "author"=>$read_single_post->post_author,
                    "date"=>$read_single_post->post_date,
                    "image"=>$read_single_post->post_image,
                    "content"=>$read_single_post->post_content,
                    "tags"=>$read_single_post->post_tags,
                    "status"=>$read_single_post->post_status,
                    "comment_count"=>$read_single_post->post_comment_count
            ]; 
            echo json_encode($post_read);
            }
        }else{
            echo json_encode(['message'=>'no record found']);
        }
    }


?>