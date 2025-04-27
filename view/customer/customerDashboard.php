<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rogen N. Marcy Store| Customer Dashboard</title>
    <link rel="stylesheet" href="../../public/styles/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="top-nav">
        <div class="logo">Rogen N. Marcy Store (Customer)</div>
    </div>

    <aside class="sidebar">
        <ul class="nav-links">
            <li><a href="#" data-target="dashboard" class="active">Dashboard</a></li>
            <li><a href="#" data-target="products">Products</a></li>
            <li><a href="#" data-target="history">History</a></li>
            <li><a href="#" data-target="settings">Settings</a></li>
        </ul>

        <div class="logout-container">
            <a href="#" class="logout-link">Log Out</a>
        </div>
    </aside>

    <main class="main-container">
        <section id="dashboard" class="active">
            <div class="main-content">
                <h1 id="welcome-message">Welcome Customer!</h1>
                <p>Click on side tabs to get started.</p>
            </div>
        </section>

        <section id="products">
            <div class="main-content">
                <h1>Available Products</h1>
                <div id="stock-products-container">
                    <p>Loading products...</p>
                </div>
            </div>
        </section>

        <section id="history">
            <div class="main-content">
                <h1>Order History</h1>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order History</th>
                        </tr>
                    </thead>
                    <tbody id="history-tbody">
                        <tr><td>No history yet</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="settings">
            <div class="main-content">
                <h1>Settings</h1>
                <button class="settings-button">Manage Lists</button>
                <button class="settings-button">Change Username/Password</button>
            </div>
        </section>
    </main>
</body>
<script src="../../public/scripts/customer_scripts.js"></script>
</html>