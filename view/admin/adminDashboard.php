<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockWise| AdminDashboard</title>
    <link rel="stylesheet" href="../../public/styles/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="top-nav">
        <div class="logo">StockWise (Admin)</div>
    </div>

    <aside class="sidebar">
        <ul class="nav-links">
            <li><a href="#" data-target="dashboard" class="active">Dashboard</a></li>
            <li><a href="#" data-target="inventory">Inventory</a></li>
            <li><a href="#" data-target="sales-reports">Reports</a></li>
            <li><a href="#" data-target="settings">Settings</a></li>
        </ul>

        <div class="logout-container">
            <a href="#" class="logout-link">Log Out</a>
        </div>
    </aside>

    <main class="main-container">
        <section id="dashboard" class="active">
            <div class="main-content">
                <h1 id="welcome-message">Welcome Admin!</h1>
                <p>Click on side tabs to get started.</p>
            </div>
        </section>

        <section id="inventory">
            <div class="main-content">
                <h1>Inventory</h1>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name of Product</th>
                            <th>Price</th>
                            <th>Stock Qnty</th>
                        </tr>
                    </thead>
                    <tbody id="inventory-tbody"></tbody>
                </table>
            </div>

            <button id="add-product-button" class="button primary">Add Product</button>

            <div id="add-product-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Add New Product</h3>
                    <form id="add-product-form">
                        <div class="form-group">
                            <label for="product-name">Product Name:</label>
                            <input type="text" id="product-name" name="product-name" required>
                        </div>
                        <div class="form-group">
                            <label for="product-description">Description:</label>
                            <input type="text" id="product-description" name="product-description" required>
                        </div>
                        <div class="form-group">
                            <label for="product-price">Price:</label>
                            <input type="number" id="product-price" name="product-price" required min="0">
                        </div>
                        <div class="form-group">
                            <label for="product-stock">Stock Quantity:</label>
                            <input type="number" id="product-stock" name="product-stock" required min="0">
                        </div>
                        <button type="submit" class="button primary">Add Product</button>
                    </form>
                </div>
            </div>
        </section>

        <section id="sales-reports">
            <div class="main-content">
                <h1>Sales Reports</h1>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Sales</th>
                        </tr>
                        <tbody id="reports-tbody"></tbody>
                    </thead>
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

<script src="../../public/scripts/admin_scripts.js"></script>
</html>