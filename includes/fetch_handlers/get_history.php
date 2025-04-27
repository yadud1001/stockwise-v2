<?php
session_start();
require_once '../dbh-inc.php';

try {
    $userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
    $isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === 'admin';

    $query = "SELECT o.order_date, o.order_details";
    if ($isAdmin) {
        $query .= ", u.username as customer_username FROM order_history o INNER JOIN users u ON o.customer_id = u.user_id";
        $orderBy = "ORDER BY o.order_date ASC"; // Most recent at the bottom for admin
    } else {
        $query .= " FROM order_history o WHERE o.customer_id = :userId";
        $orderBy = "ORDER BY o.order_date ASC"; // Oldest at the bottom for customer.
    }

    $query .= " " . $orderBy;

    $stmt = $pdo->prepare($query);
    if (!$isAdmin && $userId) {
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    }
    $stmt->execute();
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($history);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    http_response_code(500);
}