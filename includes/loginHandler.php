<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $username = $_POST['username'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    if (empty($username) || empty($pass) || empty($role)) {
        die("Missing login credentials.");
    }

    try {
        require_once 'dbh-inc.php';

        // Retrieve the user from the database based on the entered username and role.
        $query = "SELECT user_id, username, pass FROM users WHERE username = :username AND role = :role;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify if the user exists and the password is correct.
        if ($user && password_verify($pass, $user['pass'])) {

            // Store user information in the session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $role;

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Redirect based on the user's role
            if ($role === 'admin') {
                header('Location: ../view/admin/adminDashboard.php');
                die();
            } elseif ($role === 'customer') {
                header('Location: ../view/customer/customerDashboard.php');
                die();
            } else {
                die("Invalid user role.");
            }
        } else {
            // Authentication failed
            header('Location: ../login.php?error=invalidcredentials');
            die();
        }

    } catch (PDOException $e) {
        die('Login failed: ' . $e->getMessage());
    } finally {
        // Ensure these are executed even if die() was called
        if (isset($stmt)) {
            $stmt = null;
        }
        if (isset($pdo)) {
            $pdo = null;
        }
    }
} else {
    header('Location: ../login.php');
    die();
}