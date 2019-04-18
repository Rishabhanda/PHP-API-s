<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');

   // receiving headers
   $headers = getallheaders();

   include '../../includes/Db.php';
   include '../../models/Users.php';

   //including token 
   include '../../tokens/token/validate-token.php';

    //Breaking string into array and taking out token from it
    //Token is in form "Bearer A.hie.385"
    if($headers && $headers['Authorization']){
        $exploding_arr = explode(" ", $headers['Authorization']);
    
         $token = $exploding_arr[1];

        if(validate_token($token)){
            $db_connect = new Database();
            $db_connection = $db_connect->connection();
            $reading_all_users = new Users($db_connection);

            $stmt = $reading_all_users->read_all_users();
            $num_of_rows_selected = $stmt->rowCount();

            if($num_of_rows_selected){
                $user_data['data'] = [];
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $reading_all_users->user_id=$row['user_id'];
                    $reading_all_users->user_username=$row['user_username']; 
                    $reading_all_users->user_image=$row['user_image']; 
                    $reading_all_users->user_firstname=$row['user_firstname']; 
                    $reading_all_users->user_lastname=$row['user_lastname']; 
                    $reading_all_users->user_role=$row['user_role']; 
                    $reading_all_users->user_email=$row['user_email'];
                    $user = array(
                        'userId'=>$reading_all_users->user_id,
                        'userName'=>$reading_all_users->user_username,
                        'userImage'=>$reading_all_users->user_image,
                        'userFirstName'=>$reading_all_users->user_firstname,
                        'userLastName'=>$reading_all_users->user_lastname,
                        'userRole'=>$reading_all_users->user_role,
                        'userEmail'=>$reading_all_users->user_email
                    ); 
                    array_push($user_data['data'],$user);
                }
                http_response_code(200);//OK
                echo json_encode($user_data);
            } else{
                echo json_encode(array('message'=>'no records found'));
            }
        }else{
            echo json_encode(array('message'=>'you are not authticated'));
        }
    }
?>