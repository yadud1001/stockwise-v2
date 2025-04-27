<?php
session_start();
require_once '../dbh-inc.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    $error_message = "Session error: User not logged in";
    error_log($error_message, 0);
    send_json_response(['error' => $error_message], 401);
    exit;
}

$userId = $_SESSION["user_id"];
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['date'], $data['history'], $data['productId'], $data['quantity'])) {
    $error_message = "Missing data: date, history, productId, and quantity are required";
    error_log($error_message, 0);
    send_json_response(['error' => $error_message], 400);
    exit;
}

$date = $data['date'];
$historyEntry = $data['history'];
$productId = $data['productId'];
$quantity = $data['quantity'];

try {
    // Insert into order_history
    $query = "INSERT INTO order_history (customer_id, order_date, order_details) VALUES (:userId, :orderDate, :historyEntry)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':orderDate', $date);
    $stmt->bindParam(':historyEntry', $historyEntry);
    $stmt->execute();

    // Update product stock (reduce quantity)
    $updateQuery = "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE product_id = :productId";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':quantity', $quantity);
    $updateStmt->bindParam(':productId', $productId);
    $updateStmt->execute();

    $message = ($updateStmt->rowCount() > 0)
        ? 'Purchase recorded and stock updated.'
        : 'Purchase recorded, but stock was not updated (product may not exist or quantity is zero).';
    send_json_response(['success' => true, 'message' => $message]);

} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
    error_log($error_message, 0);
    send_json_response(['error' => $error_message], 500);
}

// Function to send JSON response
function send_json_response($data, $http_code = 200)
{
    header('Content-Type: application/json');
    http_response_code($http_code);
    echo json_encode($data);
    exit; // Ensure no further output
}