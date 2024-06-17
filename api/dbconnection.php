<?php
require 'config.php';
try{
 $connection=new PDO("mysql:host=$server;dbname=$dbname",$user,$password);
 $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
 http_response_code(200);
 $server_response_great=array(
    "code"=>http_response_code(200),
    "status"=>true,
    "message"=>"connected",
 );
//echo json_encode($server_response_great);
}
catch(PDOException $e){
    http_response_code(404);
    $server_response_great=array(
       "code"=>http_response_code(404),
       "status"=>false,
       "message"=>"could not connect".$e->getMessage(),
    );
//echo json_encode($server_response_great);
}









?>