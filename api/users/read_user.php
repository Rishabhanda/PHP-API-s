<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: multipart/form-data');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');


    include_once '../../includes/Db.php';
    include_once '../../models/Users.php';

    //jwt files 
    include_once '../../vendor/firebase/php-jwt/src/BeforeValidException.php';
    include_once '../../vendor/firebase/php-jwt/src/ExpiredException.php';
    include_once '../../vendor/firebase/php-jwt/src/JWT.php';
    include_once '../../vendor/firebase/php-jwt/src/SignatureInvalidException.php';
    use \Firebase\JWT\JWT;
    

    $db_connect = new Database();
    $db_connection = $db_connect->connection();
    $read_user_login = new Users($db_connection);

    $data = json_decode(file_get_contents('php://input'));
    $read_user_login->user_email = filter_var($data->email, FILTER_VALIDATE_EMAIL);

    //password algorithm and salt 
    $options = [
        'salt'=>'as3f90nbhlpvf45678kjn0',
        'cost'=>10
    ];

    $read_user_login->user_password = password_hash($data->password, PASSWORD_BCRYPT, $options);

    $stmt = $read_user_login->read_user();
    $number_of_effected_rows = $stmt->rowCount();

    if($number_of_effected_rows){

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $read_user_login->user_username = $row['user_username'];
            $read_user_login->user_email = $row['user_email'];
            $read_user_login->user_lastname = $row['user_lastname'];
            $read_user_login->user_role = $row['user_role'];
            $read_user_login->user_firstname = $row['user_firstname'];
            

        }
        //generating token 
            include '../../tokens/token/Creating-token.php';
            $token = new Token();
            $token_arr = $token->generate_token($read_user_login->user_email,$read_user_login->user_username,$read_user_login->user_lastname,$read_user_login->user_role);
            $key = $token->key();
            $jwt = JWT::encode($token_arr, $key);

        //generating refresh token
            include '../../tokens/token/Creating-refresh-token.php';
            $token_refresh = new RefreshToken();
            $token_refresh_arr = $token_refresh->generate_refresh_token($read_user_login->user_firstname);
            $key_refresh = $token_refresh->key_return_refresh_token();
            $jwt_refresh = JWT::encode($token_refresh_arr, $key_refresh);

            //showing json
            echo json_encode(['username'=>$read_user_login->user_username,"access_token"=>$jwt,"refresh_token"=>$jwt_refresh]);
        
    }else{
        if($stmt){
            echo json_encode(['message'=>'user not found']);
        }
    }





?>