<?php
require_once 'dbh-inc.php';

try {
    $query = "SELECT product_id, name, description, price, stock_quantity FROM products WHERE stock_quantity > 0";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($products);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to fetch products: ' . $e->getMessage()]);
}