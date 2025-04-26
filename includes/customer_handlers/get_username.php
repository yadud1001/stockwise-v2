<?php
session_start();
require_once '../dbh-inc.php';

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];

    try {
        $query = "SELECT username FROM users WHERE user_id = :userId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $username = $row["username"];
            header('Content-Type: application/json');
            echo json_encode(['username' => $username]);
        } else {
            // User not found
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User not found']);
            http_response_code(404);
        }
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