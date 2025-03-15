<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        echo json_encode(['error' => 'ID is required.']);
        exit;
    }

    $id = $data['id'];

    try {
        require_once '../dbh-inc.php';

        $query = "DELETE FROM customer_order_history WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}