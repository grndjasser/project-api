<?php
header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
if($_SERVER['REQUEST_METHOD']=="POST"){
   $data=json_decode(file_get_contents('php://input'),true);
    if(!empty($data['email']) && !empty($data['password'])){
        $email=filter_var($data['email'],FILTER_SANITIZE_EMAIL);
        $pass=filter_var($data['password'],FILTER_SANITIZE_SPECIAL_CHARS);
        try{
            require 'dbconnection.php';
            $sql_find_user="SELECT * FROM users WHERE email='$email'";
            $statment=$connection->prepare($sql_find_user);
            $statment->execute();
            $user_flag=$statment->rowCount();
            if($user_flag>0){
                $row=$statment->fetch(PDO::FETCH_ASSOC);
               if(password_verify($pass,$row['password'])){
               
                $sessionlifetime=3600;
                session_set_cookie_params($sessionlifetime);
                session_start();
                $_SESSION['id']=$row['id'];
                $_SESSION['username']=$row['user_name'];
                $_SESSION['email']=$row['email'];
                  http_response_code(200);
                  $server_response_great=array(
                      "code"=>http_response_code(404),
                      "status"=>true,
                      "message"=>"loged in!",
                  );
                  echo json_encode($server_response_great);
               }else{
                http_response_code(404);
                $server_response_error=array(
                    "code"=>http_response_code(404),
                    "status"=>false,
                    "message"=>"invalid email\password",
                );
                echo json_encode($server_response_error);
               }
            }
            else{
                http_response_code(404);
                $server_response_error=array(
                    "code"=>http_response_code(404),
                    "status"=>false,
                    "message"=>"invalid email\password",
                );
                echo json_encode($server_response_error);
            }
        }
        catch(PDOException $e){
            http_response_code(404);
        $server_response_error=array(
            "code"=>http_response_code(404),
            "status"=>false,
            "message"=>"opps!".$e->getMessage(),
        );
        echo json_encode($server_response_error);
        }
        
    }
    else{
        http_response_code(404);
        $server_response_error=array(
            "code"=>http_response_code(404),
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