<?php
session_start();
require_once '../dbh-inc.php';

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];

    try {
        $query = "SELECT order_date, order_details FROM order_history WHERE customer_id = :userId ORDER BY order_date ASC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($history);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        http_response_code(500);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Session error: User not logged in']);
    http_response_code(401);
}