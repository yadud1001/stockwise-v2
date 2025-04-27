<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log('Login POST data: ' . print_r($_POST, true), 0); // Log POST data

    $username = trim($_POST['username']);
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    if (empty($username) || empty($pass) || empty($role)) {
        header('Location: ../../login.php?error=missingcredentials');
        die();
    }

    try {
        require_once '../dbh-inc.php';

        $query = "SELECT user_id, username, pass, role FROM users WHERE username = :username AND role = :role";
        error_log('Executing query: ' . $query, 0); // Log the query
        $stmt = $pdo->prepare($query);
        if (!$stmt) {
            header('Location: ../../login.php?error=stmtprepfailed');
            die();
        }
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        error_log('Binding username: ' . $username . ', role: ' . $role, 0); // Log bound parameters
        $stmt->execute();

        $rowCount = $stmt->rowCount();
        error_log('Number of rows found: ' . $rowCount, 0); // Log row count
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['pass'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            session_regenerate_id(true);

            error_log('Login successful for user ID: ' . $user['user_id'] . ', role: ' . $user['role'], 0); // Log successful login

            if ($user['role'] === 'admin') {
                header('Location: ../../view/admin/adminDashboard.php');
                die();
            } elseif ($user['role'] === 'customer') {
                header('Location: ../../view/customer/customerDashboard.php');
                die();
            } else {
                die("Invalid user role in database.");
            }
        } else {
            error_log('Authentication failed for username: ' . $username . ', role: ' . $role, 0); // Log failed authentication
            header('Location: ../../login.php?error=invalidcredentials');
            die();
        }
    } catch (PDOException $e) {
        error_log('Database error during login: ' . $e->getMessage(), 0);
        header('Location: ../../login.php?error=dberror');
        die();
    } finally {
        if (isset($stmt)) {
            $stmt = null;
        }
        if (isset($pdo)) {
            $pdo = null;
        }
    }
} else {
    header('Location: ../../login.php');
    die();
}