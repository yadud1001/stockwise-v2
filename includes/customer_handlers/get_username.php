<?php
session_start(); // Start the session to access $_SESSION variables
require_once '../dbh-inc.php'; // Include your database connection file

if (isset($_SESSION["user_id"])) { // Corrected: Check for user ID
    $userId = $_SESSION["user_id"];

    try {
        $query = "SELECT username FROM users WHERE user_id = :userId"; // Corrected query
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
            http_response_code(404); // Not Found
        }
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        http_response_code(500); // Internal Server Error
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Session error: User not logged in']); // More specific error
    http_response_code(401); // Unauthorized
}