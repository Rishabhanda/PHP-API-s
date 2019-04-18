<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');


    // receiving headers
    $headers = getallheaders();

    include_once '../../includes/Db.php';
    include_once '../../models/Users.php';

    //including token 
    include '../../tokens/token/validate-token.php';

    //Breaking string into array and taking out token from it
    //Token is in form "Bearer A.hie.385"
    if($headers && $headers['Authorization']){
    $exploding_arr = explode(" ", $headers['Authorization']);

    $token = $exploding_arr[1];
    
    //validating the token before accessing the api
    if(validate_token($token)){
        $role = validate_token($token);
        if($role =='admin'){
            $db_connect = new Database();
            $db_connection = $db_connect->connection();
            $update_role = new Users($db_connection);

            if(isset($_GET['p_id'])){
                $update_role->user_id = $_GET['p_id'];
                $data_from_client = json_decode(file_get_contents('php://input'));
                $update_role->user_role = $data_from_client->role;
                $stmt = $update_role->update_user_role();
                $num = $stmt->rowCount();
                if($num){
                    echo json_encode(['message'=>'user staus updated']);
                }else{
                    echo json_encode(['message'=>'user status is not updated']);
                }
            }
        } else{
            echo json_encode(array('message'=>'you are not authorized'));
        }
    }else{
        echo json_encode(array('message'=>'you  are not authenticated'));
    }
}
?>