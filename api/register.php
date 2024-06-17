<?php
header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
if($_SERVER['REQUEST_METHOD']=="POST"){
    $data=json_decode(file_get_contents('php://input'),true);
    if(!empty($data['username']) && !empty($data['email']) && !empty($data['password']) && !empty($data['phone']) && !empty($data['is_customer'])){
       $name=filter_var($data['username'],FILTER_SANITIZE_SPECIAL_CHARS);
       $email=filter_var($data['email'],FILTER_SANITIZE_EMAIL);
       $pass=filter_var($data['password'],FILTER_SANITIZE_SPECIAL_CHARS);
       $phone=filter_var($data['phone'],FILTER_SANITIZE_NUMBER_INT);
       $is_customer=filter_var($data['is_customer'],FILTER_SANITIZE_SPECIAL_CHARS);
       try{
        $user_id=rand(1,1000000000);
        require 'dbconnection.php';
        $sql_find_user="SELECT * FROM users WHERE email='$email' or id='$user_id'";
        $statment=$connection->prepare($sql_find_user);
        $statment->execute();
        $dublicate_user_flag=$statment->rowCount();
        if($dublicate_user_flag>0){
            http_response_code(400);
            $server_response_error=array(
                "code"=>http_response_code(400),
                "status"=>false,
                "message"=>"user already exist!",
            );
            echo json_encode($server_response_error);
        }else{
            $hash=password_hash($pass,PASSWORD_DEFAULT);
            $sql_insert="INSERT INTO users(id,user_name,email,password,phone,is_customer) values('$user_id','$name','$email','$hash','$phone','$is_customer')";
            $statment=$connection->prepare($sql_insert);
            $statment->execute();
            http_response_code(200);
            $server_response_great=array(
                "code"=>http_response_code(200),
                "status"=>true,
                "message"=>"user created!"
            );
            echo json_encode($server_response_great);
        }
       }
       catch(PDOException $e){
        http_response_code(400);
        $server_response_error=array(
            "code"=>http_response_code(400),
            "status"=>false,
            "message"=>"opps!".$e->getMessage(),
        );
        echo json_encode($server_response_error);
       }
    }
    else{
        http_response_code(400);
    $server_response_error=array(
        "code"=>http_response_code(400),
        "status"=>false,
        "message"=>"wrong api parameters!",
    );
    echo json_encode($server_response_error);
    }

}
else{
    http_response_code(400);
    $server_response_error=array(
        "code"=>http_response_code(400),
        "status"=>false,
        "message"=>"bad request",
    );
    echo json_encode($server_response_error);
}

















?>