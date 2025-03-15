<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $date = $data['date'];
    $report = $data['report'];

    try {
        require_once '../dbh-inc.php';

        $query = "INSERT INTO admin_sales_reports (date, report) VALUES (:date, :report);";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':report', $report);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}