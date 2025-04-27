<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rogen N. Marcy Store| Login</title>
    <link rel="stylesheet" href="public/styles/form.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="includes/form_handlers/loginHandler.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" autocomplete="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="pass" autocomplete="current-password" required>
            </div>
            <div class="input-group">
                <label for="role">Role:</label>
                <select name="role" id="role" autocomplete="role">
                    <option value="">Select</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
            <button type="submit" class="login-button">Login</button>
            <p class="switch-form">Doesn't have an account? <a href="signup.php">Signup</a></p>
        </form>
    </div>
</body>
</html>