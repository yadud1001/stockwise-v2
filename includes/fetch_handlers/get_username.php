<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../dbh-inc.php';
session_start();

try {
    if (!isset($_SESSION["user_id"])) {
        throw new Exception('Session error: User not logged in');
    }

    $userId = $_SESSION["user_id"];
    $query = "SELECT username, role FROM users WHERE user_id = :userId";
    $stmt = $pdo->prepare($query);

    if (!$stmt) {
        throw new Exception('Database error: ' . implode(" ", $pdo->errorInfo()));
    }

    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        throw new Exception('User not found');
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['username' => $row["username"], 'role' => $row["role"]]);

} catch (Exception $e) {
    $errorMessage = ['error' => $e->getMessage()];
    error_log("Error: " . $e->getMessage(), 0); // Log the error
    echo json_encode($errorMessage);
    http_response_code(
        $e->getMessage() === 'User not found' ? 404 :
        (strpos($e->getMessage(), 'Session error') !== false ? 401 : 500)
    );
}