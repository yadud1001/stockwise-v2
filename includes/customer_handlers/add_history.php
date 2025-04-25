<?php
session_start();
require_once '../dbh-inc.php';

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['date']) && isset($data['history'])) {
        $date = $data['date'];
        $historyEntry = $data['history'];

        try {
            $query = "INSERT INTO order_history (customer_id, order_date, order_details) VALUES (:userId, :orderDate, :historyEntry)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':orderDate', $date);
            $stmt->bindParam(':historyEntry', $historyEntry);
            $stmt->execute();

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            http_response_code(500);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing data: date and history are required']);
        http_response_code(400);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Session error: User not logged in']);
    http_response_code(401);
}