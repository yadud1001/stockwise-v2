<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>StockWise| Home</title>
        <link rel="stylesheet" href="public/index.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <nav>
            <div class="logo">StockWise</div>
            <button class="login-btn" onclick="redirectTo('login.php')">Login</button>
        </nav>

        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to StockWise Inventory</h1>
                <p>Manage your products efficiently.</p>
                <button class="cta-button" onclick="redirectTo('login.php')">View Inventory</button>
            </div>
        </section>

        <footer>
            <p>&copy; 2025 StockWise</p>
        </footer>
    </body>

    <script>
        function redirectTo(targetUrl) {
            window.location.href = targetUrl;
        }
    </script>
</html>