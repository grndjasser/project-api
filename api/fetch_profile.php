<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    require 'dbconnection.php';

    try {
        $sql_fetch_orders = "SELECT * FROM orders ";
        $statement_orders = $connection->prepare($sql_fetch_orders);
        $statement_orders->execute();
        $orders = $statement_orders->fetchAll(PDO::FETCH_ASSOC);

        // Fetch products
        $sql_fetch_products = "SELECT * FROM products ";
        $statement_products = $connection->prepare($sql_fetch_products);
        $statement_products->execute();
        $products = $statement_products->fetchAll(PDO::FETCH_ASSOC);

        if ( !empty($products) && !empty($orders)) {
            echo json_encode([
                'products' => $products,
                "orders"=>$orders
            ]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode([
                "code" => http_response_code(404),
                "status" => false,
                "message" => "No orders or products found"
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "code" => http_response_code(500),
            "status" => false,
            "message" => "Oops! " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode([
        "code" => http_response_code(400),
        "status" => false,
        "message" => "Bad request"
    ]);
}
?>
