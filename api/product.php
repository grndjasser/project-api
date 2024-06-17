<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
if($_SERVER['REQUEST_METHOD']=="POST"){
   $data=json_decode(file_get_contents('php://input'),true);
   if(!empty($data['product_name']) && !empty($data['price']) && !empty($data['description']) && !empty($data['seller_id'])){
     $product_name=filter_var($data['product_name'],FILTER_SANITIZE_SPECIAL_CHARS);
     $price=filter_var($data['price'],FILTER_SANITIZE_NUMBER_FLOAT);
     $description=filter_var($data['description'],FILTER_SANITIZE_SPECIAL_CHARS);
     $seller_id=filter_var($data['seller_id'],FILTER_SANITIZE_NUMBER_INT);
     try{
        require 'dbconnection.php';
        $product_id=rand(1,1000000000);
        $sql_find="SELECT * FROM products WHERE product_id='$product_id'";
        $statment=$connection->prepare($sql_find);
        $statment->execute();
        $dublicate_product_flag=$statment->rowCount();
        if($dublicate_product_flag>0){
            http_response_code(404);
    $server_response_error=array(
        "code"=>http_response_code(404),
        "status"=>false,
        "message"=>"try again!",
    );
    echo json_encode($server_response_error);
        }else{
            $sql_insert="INSERT INTO products(product_name,product_id,seller_id,description,price) values('$product_name','$product_id','$seller_id','$description','$price')";
            $statment=$connection->prepare($sql_insert);
            $statment->execute();
            http_response_code(200);
            $server_response_great=array(
                "code"=>http_response_code(200),
                "status"=>true,
                "message"=>"product added!",
            );
            echo json_encode($server_response_great);
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
    http_response_code(404);
    $server_response_error=array(
        "code"=>http_response_code(404),
        "status"=>false,
        "message"=>"bad request",
    );
    echo json_encode($server_response_error);
}













?>