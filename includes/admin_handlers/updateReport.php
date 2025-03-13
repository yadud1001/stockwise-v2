<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['date']) || !isset($data['history'])) {
        echo json_encode(['error' => 'ID, date, and report are required.']);
        exit;
    }

    $id = $data['id'];
    $date = $data['date'];
    $report = $data['report'];

    try {
        require_once '../dbh-inc.php';

        $query = "UPDATE sales_report SET date = :date, history = :history WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':report', $report);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}