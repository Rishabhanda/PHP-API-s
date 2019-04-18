<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Authorization');

    // receiving headers
    $headers = getallheaders();
    
    // print_r($headers);

    include '../../includes/Db.php';
    include '../../models/Posts.php';

    //including token 
    include '../../tokens/token/validate-token.php';

    //Breaking string into array and taking out token from it
    //Token is in form "Bearer A.hie.385"
    $exploding_arr = explode(" ", $headers['Authorization']);

    $token = $exploding_arr[1];

    //validating the token before accessing the api
    if(validate_token($token)){
        $role = validate_token($token);
        if($role =='admin'){
            $db_connect = new Database();
            $db_connection = $db_connect->connection();
            $delete_post = new Posts($db_connection);

            if(isset($_GET['p_id'])){
                $delete_post->post_id = filter_var($_GET['p_id'] , FILTER_SANITIZE_NUMBER_INT);
                $stmt = $delete_post->delete_post();
                $num_of_affectd_rows = $stmt->rowCount();
                if($num_of_affectd_rows){
                    echo json_encode(['message'=>'post deleted successfully']);
                }else{
                    echo json_encode(['message'=>'no record found']);
                }
            }
        } else{
            echo json_encode(array('message'=>'you are not authorized'));
        }
} else{

    // http_response_code(401);//Unauthorized()
    // header('WWW-Authenticate: Bearer realm="cms", error="invalid token", error_description="Either access token is expired or not issued"');
    echo json_encode(array('message'=>'you are not authenticated')); 

}
?>