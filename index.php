<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rogen N. Marcy Store| Home</title>
        <link rel="stylesheet" href="public/styles/index.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <nav>
            <div class="logo">Rogen N. Marcy Store</div>

            <div class="nav-btns-container">
            <button class="login-btn" onclick="redirectTo('login.php')">Login |</button>
            <button class="login-btn" onclick="redirectTo('signup.php')">Signup</button>
            </div>
        </nav>

        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to Rogen N. Marcy Store</h1>
                <p>Manage your products efficiently.</p>
                <button class="cta-button" onclick="redirectTo('login.php')">View Store</button>
            </div>
        </section>

        <footer>
            <p>&copy; 2025 Rogen N. Marcy Store</p>
        </footer>
    </body>

    <script>
        function redirectTo(targetUrl) {
            window.location.href = targetUrl;
        }
    </script>
</html>