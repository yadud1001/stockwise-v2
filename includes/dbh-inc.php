<?php
$dsn = 'mysql:host=localhost;dbname=stockwise_project';
$dbUsername = 'root';
$dbPassword = '';

try {
    $pdo = new PDO($dsn, $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    $error_message = "Database connection failed: " . $e->getMessage();
    error_log($error_message, 0);
    echo "Database connection error: " . $error_message;
    exit();
}