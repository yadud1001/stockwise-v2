<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['date']) || !isset($data['history'])) {
        echo json_encode(['error' => 'ID, date, and history are required.']);
        exit;
    }

    $id = $data['id'];
    $date = $data['date'];
    $history = $data['history'];

    try {
        require_once '../dbh-inc.php';

        $query = "UPDATE order_history SET date = :date, history = :history WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':history', $history);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}