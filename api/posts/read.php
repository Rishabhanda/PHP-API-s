<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');


    include '../../includes/Db.php';
    include '../../models/Posts.php';

    $db_connect = new Database();
    $db_connection = $db_connect->connection();
    $read_all_posts = new Posts($db_connection);

    $stmt = $read_all_posts->read_post();
    $number_of_records_found = $stmt->rowCount();

    if($number_of_records_found){
        $post = [];
        $post['data'] = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $read_all_posts->post_id =  $row['post_id'];
            $read_all_posts->post_category_id =  $row['post_category_id'];
            $read_all_posts->post_title =  $row['post_title'];
            $read_all_posts->post_author =  $row['post_author'];
            $read_all_posts->post_date =  $row['post_date'];
            $read_all_posts->post_image =  $row['post_image'];
            $read_all_posts->post_content =  $row['post_content'];
            $read_all_posts->post_tags =  $row['post_tags'];
            $read_all_posts->post_status =  $row['post_status'];
            $read_all_posts->post_comment_count =  $row['post_comment_count'];
            $post_read = [
                "id"=>$read_all_posts->post_id,
                "cat_name"=>$row['cat_title'],
                "cat_id"=>$read_all_posts->post_category_id,
                "title"=>$read_all_posts->post_title,
                "author"=>$read_all_posts->post_author,
                "date"=>$read_all_posts->post_date,
                "image"=>$read_all_posts->post_image,
                "content"=>$read_all_posts->post_content,
                "tags"=>$read_all_posts->post_tags,
                "status"=>$read_all_posts->post_status,
                "comment_count"=>$read_all_posts->post_comment_count
            ];
            array_push($post['data'],$post_read);
        }
        echo json_encode($post);
    }else{
        echo json_encode(['message'=>'no records found']);
    }
?>