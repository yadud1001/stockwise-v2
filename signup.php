<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockWise| Sign Up</title>
    <link rel="stylesheet" href="public/form.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form action="includes/signupHandler.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" autocomplete="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="pass" autocomplete="new-password" required>
            </div>
            <div class="input-group">
                <label for="role">Role:</label>
                <select name="role" id="role" autocomplete="role">
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                    </select>
            </div>
            <button type="submit" class="signup-button">Sign Up</button>
            <p class="switch-form">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>