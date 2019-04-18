<?php

    include_once '../../vendor/firebase/php-jwt/src/BeforeValidException.php';
    include_once '../../vendor/firebase/php-jwt/src/ExpiredException.php';
    include_once '../../vendor/firebase/php-jwt/src/JWT.php';
    include_once '../../vendor/firebase/php-jwt/src/SignatureInvalidException.php';
    use \Firebase\JWT\JWT;

    include '../../tokens/token/Creating-token.php';
    include '../../tokens/token/Creating-refresh-token.php';
    //token recieved


    function validate_token($token){
        //retrieving key
        $get_key = new Token();
        $key = $get_key->key();   
        //token recieved from client
        try{        
            $token_recieved = $token;
            $decoded = JWT::decode($token_recieved, $key, array('HS256'));

            if($decoded && $decoded->email && $decoded->lastname && $decoded->name){
                return $decoded->role;
            }else{
                return false;
            }
        } catch(Exception $e) {
            echo 'Error: ' .$e->getMessage();
            return false;
        }
    }

    function validate_refresh_token($token_refresh){
        $get_refresh_token_key = new RefreshToken();
        $key = $get_refresh_token_key->key_return_refresh_token();
        //token recieved from client
        try{
            $decoded = JWT::decode($token_refresh, $key, array('HS256'));
            if($decoded && $decoded->firstname){
                return $decoded->firstname;
            }else{
                return  false;
            }
        }catch(Exception $e){
            echo 'Error: ' .$e->getMessage();
            return false;
        }
    }

?>






