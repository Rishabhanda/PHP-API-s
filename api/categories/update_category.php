<?php 

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');
    

    // receiving headers
    $headers = getallheaders();

    include '../../includes/Db.php';
    include '../../models/Categories.php';

    //including token 
    include '../../tokens/token/validate-token.php';

    //Breaking string into array and taking out token from it
    //Token is in form "Bearer A.hie.385"
    $exploding_arr = explode(" ", $headers['Authorization']);

    $token = $exploding_arr[1];

    //validating the token before accessing the api
    if(validate_token($token)){
        $role = validate_token($token);
        if($role == 'admin'){
            $db_connect = new Database();
            $db_connection = $db_connect->connection();
            $update_category = new Categories($db_connection);

            if(isset($_GET['p_id'])){
                $data_from_client = json_decode(file_get_contents('php://input'));

                $update_category->cat_title =  filter_var($data_from_client->cat_title , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);
                $update_category->cat_id = filter_var($_GET['p_id'] , FILTER_SANITIZE_NUMBER_INT);

                $stmt = $update_category->update_category();
                $num = $stmt->rowCount();

                if($num){
                    // http_response_code(204);//No Content (request is succeded)
                    echo json_encode(['message'=>'category updated']);
                }else{
                    // http_response_code(400);//Bad Request(client error)
                    echo json_encode(['message'=>' no record found']);
                }
            }
        } else{
            echo json_encode(array('message'=>'you are not authorized'));
        }
        // if token is unvalidated then run this
    } else{
        // http_response_code(401);//Unauthorized()
        // header('WWW-Authenticate: Bearer realm="cms", error="invalid token", error_description="Either access token is expired or not issued"');
        echo json_encode(array('message'=>'you are not authenticated')); 
    }
?>