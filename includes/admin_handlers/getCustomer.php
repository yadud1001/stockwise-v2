<?php
try {
    require_once '../dbh-inc.php';

    $query = "SELECT name FROM customers;";

    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}