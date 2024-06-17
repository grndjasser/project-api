<?php
header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
if($_SERVER['REQUEST_METHOD']=="GET"){
    try{
        require 'dbconnection.php';
        $sql_fetch="SELECT * FROM orders";
        $statment=$connection->prepare($sql_fetch);
        $statment->execute();
        if($statment->rowCount()>0){
            $orders=$statment->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($orders);

        }
        else{
            http_response_code(400);
            $server_response_error=array(
                "code"=>http_response_code(400),
                "status"=>false,
                "message"=>"no orders found",
            );
            echo json_encode($server_response_error);
        }
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
    http_response_code(400);
    $server_response_error=array(
        "code"=>http_response_code(400),
        "status"=>false,
        "message"=>"bad request",
    );
    echo json_encode($server_response_error);
}