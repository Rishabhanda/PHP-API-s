<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');
    header('Accept:multipart/form-data');

    // receiving headers
    $headers = getallheaders();


    include '../../tokens/token/validate-token.php';
    include_once '../../vendor/firebase/php-jwt/src/BeforeValidException.php';
    include_once '../../vendor/firebase/php-jwt/src/ExpiredException.php';
    include_once '../../vendor/firebase/php-jwt/src/JWT.php';
    include_once '../../vendor/firebase/php-jwt/src/SignatureInvalidException.php';
    use \Firebase\JWT\JWT;

    //consuming refresh token 
    $refresh_token = $headers['Authorization'];
    
    $firstname = validate_refresh_token($refresh_token);
    if($firstname){

        include '../../includes/Db.php';
        include '../../models/Query.php';
            //jwt files 


        $db_connect = new Database();
        $db_connection = $db_connect->connection();
        $user = new Query($db_connection);
        $user->firstname = $firstname;
        $stmt = $user->finding_user();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $email = $row['user_email'];
            $lastname = $row['user_lastname'];
            $role = $row['user_role'];
            $name = $row['user_username'];
        } 

        $token_bearer = new Token();
        $token_bearer_arr = $token_bearer->generate_token($email,$name,$lastname,$role);
        $key_bearer = $token_bearer->key();
        $jwt_bearer = JWT::encode($token_bearer_arr, $key_bearer);

        $token_refresh = new RefreshToken();
        $token_refresh_arr = $token_refresh->generate_refresh_token($firstname);
        $key_refresh = $token_refresh->key_return_refresh_token();
        $jwt_refresh = JWT::encode($token_refresh_arr, $key_refresh);

        // http_response_code(200);//OK
        echo json_encode(array("access_token"=>$jwt_bearer,"refresh_token"=>$jwt_refresh));
    }else{
        // http_response_code(401);
        header('WWW-Authenticate: Bearer realm="cms", error="invalid token", error_description="Either access token is expired or not issued"');
        echo json_encode(['message'=>'you are not authenticated']);
    }

?>