<?php
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $username = $_POST['username'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    if (empty($username) || empty($pass) || empty($role)) {
        die("Missing signup data.");
    }

    // Basic username validation (you might want more robust checks)
    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        die("Invalid username format. Only letters, numbers, and underscores are allowed.");
    }

    // Basic password strength check (you should implement more rigorous checks)
    if (strlen($pass) < 6) {
        die("Password must be at least 6 characters long.");
    }

    try {
        require_once '../dbh-inc.php';

        // Check if the username already exists
        $checkUserQuery = "SELECT COUNT(*) FROM users WHERE username = :username;";
        $checkUserStmt = $pdo->prepare($checkUserQuery);
        $checkUserStmt->bindParam(':username', $username);
        $checkUserStmt->execute();

        if ($checkUserStmt->fetchColumn() > 0) {
            die("Username already exists. Please choose a different one.");
        }

        // Hash the password securely
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

        $query = 'INSERT INTO users (username, pass, role) VALUES (:username, :pass, :role);';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':pass', $hashedPassword);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        // Start the session and set session variables
        session_start();
        $_SESSION['user_id'] = $pdo->lastInsertId(); // Get the ID of the newly created user
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        session_regenerate_id(true);

        // Redirect to the appropriate dashboard
        if ($role === 'admin') {
            header('Location: ../../view/admin/adminDashboard.php');
            die();
        } elseif ($role === 'customer') {
            header('Location: ../../view/customer/customerDashboard.php');
            die();
        } else {
            die("Invalid user role after signup."); //Should not happen, but good to have.
        }

    } catch (PDOException $e) {
        $errorMessage = 'Signup failed: ' . $e->getMessage();
        error_log($errorMessage, 0);  // Log the error
        die('An error occurred. Please try again later.');
    } finally {
        if (isset($stmt)) {
            $stmt = null;
        }
        if (isset($checkUserStmt)) {
            $checkUserStmt = null;
        }
        if (isset($pdo)) {
            $pdo = null;
        }
    }
} else {
    header('Location: ../../signup.php');
    die();
}