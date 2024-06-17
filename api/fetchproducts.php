<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    try {
        require 'dbconnection.php';

        $sql_fetch = "SELECT * FROM products";
        $statement = $connection->prepare($sql_fetch);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($products);
        } else {
            http_response_code(404);
            $server_response_error = array(
                "code" => http_response_code(404),
                "status" => false,
                "message" => "No products found!"
            );
            echo json_encode($server_response_error);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $server_response_error = array(
            "code" => http_response_code(),
            "status" => false,
            "message" => "Oops! " . $e->getMessage()
        );
        echo json_encode($server_response_error);
    }
} else {
    http_response_code(400);
    $server_response_error = array(
        "code" => http_response_code(),
        "status" => false,
        "message" => "Bad request"
    );
    echo json_encode($server_response_error);
}
?>
