<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $username = $_POST['username'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    if (empty($username) || empty($pass) || empty($role)) {
        die("Missing form data.");
    }

    try {
        require_once 'dbh-inc.php';

        $query = 'INSERT INTO users (username, pass, role) VALUES (:username, :pass, :role);';

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':pass', $pass);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        $_SESSION['role'] = $role;

        $query = null;
        $stmt = null;

        if ($role === 'admin') {
            header('Location: ../page/admin/adminDashboard.php');
            die();
        } elseif ($role === 'customer') {
            header('Location: ../page/customer/customerDashboard.php');
            die();
        } else {
            die("Invalid role.");
        }
    } catch (PDOException $e) {
        die('Query failed: ' . $e->getMessage());
    }
} else {
    header('Location: ../login.php');
    die();
}