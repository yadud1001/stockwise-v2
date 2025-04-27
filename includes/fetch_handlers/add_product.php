<?php
require_once '../dbh-inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $productPrice = $_POST['product_price'];
    $productStock = $_POST['product_stock'];

    try {
        $query = "INSERT INTO products (name, description, price, stock_quantity) VALUES (:name, :description, :price, :stock)";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':name', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':description', $productDescription, PDO::PARAM_STR);
        $stmt->bindParam(':price', $productPrice, PDO::PARAM_INT);
        $stmt->bindParam(':stock', $productStock, PDO::PARAM_INT);

        $stmt->execute();

        // Send a JSON response for success
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Handle database errors
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        http_response_code(500); // Internal Server Error
    }
} else {
    // Handle invalid request method
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
    http_response_code(400); // Bad Request
}