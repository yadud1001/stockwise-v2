<?php
// Enable detailed error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
require 'dbh-inc.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $message = "Invalid request method. Use POST.";
    error_log($message, 0);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

// Validate input parameters
if (!isset($_POST['product_id'], $_POST['new_stock']) || !is_numeric($_POST['product_id']) || !is_numeric($_POST['new_stock'])) {
    $message = "Invalid or missing product_id or new_stock parameters.";
    error_log($message, 0);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

$productId = $_POST['product_id'];
$newStock = $_POST['new_stock'];

// Update stock quantity
try {
    $query = "UPDATE products SET stock = :newStock WHERE id = :productId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':newStock', $newStock, PDO::PARAM_INT);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Stock updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Stock update failed. Product not found or no change needed.']);
    }
} catch (PDOException $e) {
    // Handle query errors
    $message = "Error updating stock: " . $e->getMessage();
    error_log($message, 0);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}