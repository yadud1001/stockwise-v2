<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $date = $data['date'];
    $history = $data['history'];

    try {
        require_once '../dbh-inc.php';

        $query = "INSERT INTO order_history (date, history) VALUES (:date, :history);";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':history', $history);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}