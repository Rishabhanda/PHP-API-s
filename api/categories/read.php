<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');


    include '../../includes/Db.php';
    include '../../models/Categories.php';



    $db_connect = new Database();
    $db_connection = $db_connect->connection();
    $cat_read_all = new Categories($db_connection);

    $stmt = $cat_read_all->read();
    $num_of_rows_selected = $stmt->rowCount();

    if($num_of_rows_selected){
        $post_data['data'] = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $cat_read_all->cat_title = $row['cat_title'];
            $cat_read_all->cat_id = $row['cat_id'];
            $post = array(
            "cat_id"=> $cat_read_all->cat_id,
            "cat_title"=> $cat_read_all->cat_title
            );
        array_push($post_data['data'],$post);

        }
        http_response_code(200);//OK
        echo json_encode($post_data);
    } else{
        // http_response_code(400);//Bad Request(client error)
        echo json_encode(array('message'=>'no records found'));
    }  



?>