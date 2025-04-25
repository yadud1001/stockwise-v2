<?php
require_once 'dbh-inc.php'; // Include your database connection file

try {
    $query = "SELECT product_id, name, description, price FROM products WHERE stock_quantity > 0";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($products);

} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to fetch products: ' . $e->getMessage()]);
}