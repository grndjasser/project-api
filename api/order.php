<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
if($_SERVER['REQUEST_METHOD']=="POST"){
    $data=json_decode(file_get_contents('php://input'),true);
    if(!empty($data['product_id']) && !empty($data['price']) && !empty($data['seller_id'])){
          $prodecut_id=filter_var($data['product_id'],FILTER_SANITIZE_NUMBER_INT);
          $price=filter_var($data['price'],FILTER_SANITIZE_NUMBER_FLOAT);
          $seller_id=filter_var($data['seller_id'],FILTER_SANITIZE_NUMBER_INT);
        try{
            $order_id=rand(1,1000000000);
            require 'dbconnection.php';
            $sql_insert="INSERT INTO orders(order_id,product_id,seller_id,price) values('$order_id','$prodecut_id','$seller_id','$price')";
            $statment=$connection->prepare($sql_insert);
            $statment->execute();
            http_response_code(200);
            $server_respons_great=array(
                "code"=>http_response_code(200),
                "status"=>true,
                "message"=>"order added!",
            );
            echo json_encode($server_respons_great);
      } 
      catch(PDOException $e){
        http_response_code(500);
        $server_response_error=array(
            "code"=>http_response_code(500),
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