<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $oldName = $data['oldName'];
    $newName = $data['newName'];

    try {
        require_once '../dbh-inc.php';

        $query = "UPDATE customers SET name = :newName WHERE name = :oldName";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':oldName', $oldName);
        $stmt->bindParam(':newName', $newName);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}