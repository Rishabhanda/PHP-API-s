<?php 
    header('Access-Control-Origin-Allow; *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');

    include '../../includes/Db.php';
    include '../../models/Categories.php';

    $db_connect = new Database();
    $db_connection = $db_connect->connection();
    $cat_read_one = new Categories($db_connection);


    if(isset($_GET['p_id'])){

        $cat_read_one->cat_id = $_GET['p_id'];
        $stmt = $cat_read_one->read_one();
        $num_of_rows = $stmt->rowCount();
        if($num_of_rows){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $cat_read_one->cat_title = $row['cat_title'];
                $post_single_item = array( "id"=>$cat_read_one->cat_id, "cat_title"=>$cat_read_one->cat_title);
            }
            http_response_code(200);//OK
            echo json_encode($post_single_item);

        } else{
            //when no record found
            http_response_code(400);//Bad Request(client error)
            echo json_encode(['message'=>'oops! no record found']);
        }

    }








?>