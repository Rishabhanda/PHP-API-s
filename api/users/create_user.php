<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Authorization, X-Requested-With');


    include '../../includes/Db.php';
    include '../../models/Users.php';


            $db_connect = new Database();
            $db_connection = $db_connect->connection();
            $create_user_signup = new Users($db_connection);  

            // $data = json_decode(file_get_contents('php://input'));
            if(isset($_FILES['image']) && isset($_POST['content'])){
                //getting the content
                $data = json_decode($_POST['content']);
                //saving the images
                $image_file_name = $_FILES['image']['name'];
                $image_file_tmp = $_FILES['image']['tmp_name'];
                $my_file_loaded_location = "../../images/{$image_file_name}";
                move_uploaded_file($image_file_tmp, $my_file_loaded_location);
                //salt
                $options = [
                    'salt'=>'as3f90nbhlpvf45678kjn0',
                    'cost'=>10
                ];

                $create_user_signup->user_password = password_hash($data->password, PASSWORD_BCRYPT, $options);
                $create_user_signup->user_username = filter_var($data->username , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);
                $create_user_signup->user_firstname = filter_var($data->firstname , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);
                $create_user_signup->user_lastname = filter_var($data->lastname , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);
                $create_user_signup->user_image = $image_file_name;
                $create_user_signup->user_email = filter_var($data->email , FILTER_VALIDATE_EMAIL);

                //check if email is already existed in the db
                
                $stmt = $create_user_signup->check_email_in_db();

                $number_of_rows_affected = $stmt->rowCount();

                if($number_of_rows_affected){
                    echo json_encode(['message'=>'user already exists']);
                }else{
                    $stmt = $create_user_signup->create_user();
                    if($stmt){
                        echo json_encode(['message'=>'user created']);
                    }else{
                        echo json_encode(['message'=>'user not created']);
                    }

                }
            }
        

?>