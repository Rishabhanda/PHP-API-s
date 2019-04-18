<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Authorization');

    // receiving headers
    $headers = getallheaders();
    
    // print_r($headers);

    include '../../includes/Db.php';
    include '../../models/Categories.php';

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
        if($role == 'admin'){
            $db_connect = new Database();
            $db_connection = $db_connect->connection();
            $delete_category = new Categories($db_connection);

            if(isset($_GET['p_id'])){
                $delete_category->cat_id = $_GET['p_id'];
                $stmt = $delete_category->delete_category();
                $num = $stmt->rowCount();
                if($num){
                    if($stmt){
                        // http_response_code(200);//OK
                        echo json_encode(['message'=>'category deleted successfully']);
                    } else{
                        // http_response_code(501);//Not Implemented
                        echo json_encode(['message'=>'oops something went wrong']);
                    }
                } else{
                    // http_response_code(400);//Bad Request(client error)
                    echo json_encode(['message'=>'record  not found']);
                }
            }
        }else{
            echo json_encode(array('message'=>'you are not authorized'));
        }
    // if token is unvalidated then run this
    } 
    if(!validate_token($token)){
        // header('WWW-Authenticate: Bearer realm="cms", error="invalid token", error_description="Either access token is expired or not issued"');
        echo json_encode(array('message'=>'you are not authenticated','status'=>validate_token($token)));

        // http_response_code(401);//Unauthorized()

        
    }
    } else {
        echo json_encode('good');
    }

?>