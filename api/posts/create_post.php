<?php 
 header('Access-Control-Allow-Origin: *');
 header('Content-Type: multipart/form-data');
 header('Access-Control-Allow-Methods: POST');
 header('Access-Control-Allow-Headers: Authorization, X-Requested-With');

    // receiving headers
    $headers = getallheaders();

    include '../../includes/Db.php';
    include '../../models/Posts.php';

    //including wall of validating token
    include  '../../tokens/token/validate-token.php';

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
                $create_post = new Posts($db_connection);

                if(isset($_POST['content']) && isset($_FILES['image'])){

                    //getting content
                    $data_from_client = json_decode($_POST['content']);

                    $image_file_name = $_FILES['image']['name'];
                    $image_file_tmp = $_FILES['image']['tmp_name'];

                    $my_file_loaded_location = "../../images/{$image_file_name}";

                    move_uploaded_file($image_file_tmp, $my_file_loaded_location);


                    //$data_from_client = json_decode(file_get_contents('php://input'));

                    $create_post->post_category_id = filter_var($data_from_client->category_id , FILTER_SANITIZE_NUMBER_INT);
                    $create_post->post_title =  filter_var($data_from_client->title , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);
                    $create_post->post_author = filter_var($data_from_client->author , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);  
                    $create_post->post_image = $image_file_name;
                    $create_post->post_content = filter_var($data_from_client->content , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);
                    $create_post->post_tags = filter_var($data_from_client->tags , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);
                    // $create_post->post_comment_count = filter_var($data_from_client->comment_count , FILTER_SANITIZE_NUMBER_INT);
                    $create_post->post_comment_count = '2';
                    $create_post->post_status = filter_var($data_from_client->status , FILTER_SANITIZE_STRING , FILTER_FLAG_STRIP_HIGH);

                    $stmt = $create_post->create_post();
                    if($stmt){
                        echo json_encode(['message'=>'post is successfully created']);
                    }else{
                        echo json_encode(['message'=>'oops something went wrong, post is not created']);
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