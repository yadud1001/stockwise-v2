<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];

    try {
        require_once '../dbh-inc.php';

        $query = "DELETE FROM products WHERE name = :name";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}