<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockWise | Customer Dashboard</title>
    <link rel="stylesheet" href="../../public/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="top-nav">
        <div class="logo">StockWise (Customer)</div>
    </div>

    <aside class="sidebar">
        <ul class="nav-links">
            <li><a href="#" data-target="dashboard" class="active">Dashboard</a></li>
            <li><a href="#" data-target="products">Products</a></li>
            <li><a href="#" data-target="history">History</a></li>
            <li><a href="#" data-target="settings">Settings</a></li>
        </ul>

        <div class="logout-container">
            <a href="#" data-target="log-out" class="logout-link">Log Out</a>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Navigation bar highlight and section display ---
        const navLinks = document.querySelectorAll('.nav-links a, .logout-link');
        const sections = document.querySelectorAll('main.main-container > section');

        navLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const targetId = this.dataset.target;
                sections.forEach(section => section.classList.remove('active'));
                document.getElementById(targetId)?.classList.add('active');
                navLinks.forEach(otherLink => otherLink.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // --- Get Username and Update Welcome Message ---
        function getUsername() {
            fetch('../../includes/customer_handlers/get_username.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('welcome-message').textContent = data.error ? "Welcome Customer!" : `Welcome ${data.username}!`;
                })
                .catch(error => console.error('Error fetching username:', error));
        }
        // --- End Get Username ---

        // --- Product Display Functionality ---
        const productsContainer = document.getElementById('stock-products-container');
        function fetchStockProducts() {
            fetch('../../includes/get_stock_products.php')
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        productsContainer.innerHTML = `<p class="error">${data.error}</p>`;
                        console.error('Error fetching products:', data.error);
                        return;
                    }

                    if (data.length > 0) {
                        let productsHTML = '<div class="product-grid">';
                        data.forEach(product => {
                            productsHTML += `
                                <div class="product-card">
                                    <div>
                                        <h3>${product.name}</h3>
                                        <p class="description">${product.description}</p>
                                        <p class="price">₱${product.price}</p>
                                        <div class="quantity-selector">
                                            <label for="quantity-${product.product_id}">Quantity:</label>
                                            <select id="quantity-${product.product_id}">
                                                ${generateQuantityOptions(product.stock_quantity)}
                                            </select>
                                        </div>
                                    </div>
                                    <button class="purchase-btn"
                                            data-product-id="${product.product_id}"
                                            data-product-name="${product.name}"
                                            data-product-price="${product.price}"
                                            data-stock-quantity="${product.stock_quantity}">
                                        Purchase
                                    </button>
                                </div>
                            `;
                        });
                        productsHTML += '</div>';
                        productsContainer.innerHTML = productsHTML;

                        document.querySelectorAll('.purchase-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const productId = this.dataset.productId;
                                const productName = this.dataset.productName;
                                const productPrice = this.dataset.productPrice;
                                const quantity = parseInt(document.getElementById(`quantity-${productId}`).value);
                                const stockQuantity = parseInt(this.dataset.stockQuantity);

                                if (quantity > stockQuantity) {
                                    alert(`Insufficient stock! Only ${stockQuantity} available.`);
                                    return;
                                }

                                console.log('Added to cart product ID:', productId, 'Quantity:', quantity);
                                addPurchaseToHistory(productId, productName, productPrice, quantity, stockQuantity);
                            });
                        });
                    } else {
                        productsContainer.innerHTML = '<p>No products currently in stock.</p>';
                    }
                })
                .catch(error => {
                    productsContainer.innerHTML = `<p class="error">Failed to load products: ${error.message}</p>`;
                    console.error('Error fetching products:', error);
                });
        }

        function generateQuantityOptions(maxQuantity) {
            let options = '';
            for (let i = 1; i <= maxQuantity; i++) {
                options += `<option value="${i}">${i}</option>`;
            }
            return options;
        }
        // --- End Product Display Functionality ---

        // --- Add Purchase to History ---
        function addPurchaseToHistory(productId, productName, productPrice, quantity, stockQuantity) {
            const today = new Date();
            const date = `${today.getFullYear()}-${today.getMonth() + 1}-${today.getDate()}`;
            const historyEntry = `Purchased ${productName} for ₱${productPrice} x ${quantity}`;

            fetch('../../includes/customer_handlers/add_history.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ date, history: historyEntry, productId, quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error adding purchase to history:', data.error);
                    alert('Error adding purchase to history. Please check console.');
                } else {
                    console.log('Purchase history updated successfully.');
                    updateStockQuantity(productId, quantity, stockQuantity);
                    fetchHistory();
                    alert('Purchase added to history!');
                }
            })
            .catch(error => console.error('Error adding purchase to history:', error));
        }
        // --- End Add Purchase to History ---

        // --- Update Stock Quantity ---
        function updateStockQuantity(productId, quantity, currentStock) {
            const newStock = currentStock - quantity;
            const formData = new URLSearchParams();
            formData.append('product_id', productId);
            formData.append('new_stock', newStock.toString());

            fetch('../../includes/update_stock.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData,
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Error updating stock:', data.error);
                    alert('Error updating stock quantity. Please check console.');
                } else {
                    console.log('Stock updated successfully.');
                }
            })
            .catch(error => console.error('Error updating stock:', error));
        }
        // --- End Update Stock Quantity ---

        // --- Fetch and render history ---
        function fetchHistory() {
            fetch('../../includes/customer_handlers/get_history.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('history-tbody');
                    tableBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            const row = tableBody.insertRow();
                            row.innerHTML = `<td>${item.order_date}</td><td>${item.order_details}</td>`;
                        });
                    } else {
                        tableBody.innerHTML = `<tr><td colspan='2'>No purchase history</td></tr>`;
                    }
                })
                .catch(error => console.error('Error fetching history:', error));
        }
        // --- End Fetch and render history ---

        // --- Logout functionality ---
        document.querySelector('.logout-link').addEventListener('click', () => window.location.href = "../../login.php");

        getUsername();
        fetchStockProducts();
        fetchHistory();
    });
</script>
</body>
</html>