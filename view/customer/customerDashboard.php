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
            // for navigation bar highlight and displaying the right section content
            const navLinks = document.querySelectorAll('.nav-links a, .logout-link');
            const sections = document.querySelectorAll('main.main-container > section');

            navLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();

                    const targetId = this.dataset.target;
                    const targetSection = document.getElementById(targetId);

                    if (targetSection) {
                        sections.forEach(section => section.classList.remove('active'));
                        targetSection.classList.add('active');

                        navLinks.forEach(otherLink => otherLink.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });

            // --- Get Username and Update Welcome Message ---
            function getUsername() {
                fetch('../../includes/customer_handlers/get_username.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error fetching username:', data.error);
                            document.getElementById('welcome-message').textContent = "Welcome Customer!";
                        } else {
                            const username = data.username;
                            document.getElementById('welcome-message').textContent = `Welcome ${username}!`;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching username:', error);
                        document.getElementById('welcome-message').textContent = "Welcome Customer!";
                    });
            }
            // --- End Get Username ---

            // --- Product Display Functionality ---
            const productsContainer = document.getElementById('stock-products-container');
            function fetchStockProducts() {
                fetch('../../includes/get_stock_products.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
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
                                        <h3>${product.name}</h3>
                                        <p class="price">₱${product.price}</p>
                                        ${product.description ? `<p class="description">${product.description}</p>` : ''}
                                        <button class="purchase-btn" data-product-id="${product.product_id}" data-product-name="${product.name}" data-product-price="${product.price}">Purchase</button>
                                    </div>
                                `;
                            });
                            productsHTML += '</div>';
                            productsContainer.innerHTML = productsHTML;

                            const addToCartButtons = document.querySelectorAll('.purchase-btn');
                            addToCartButtons.forEach(button => {
                                button.addEventListener('click', function() {
                                    const productId = this.dataset.productId;
                                    const productName = this.dataset.productName;
                                    const productPrice = this.dataset.productPrice;
                                    console.log('Added to cart product ID:', productId);
                
                                    addPurchaseToHistory(productId, productName, productPrice);
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
            // --- End Product Display Functionality ---

            // --- Add Purchase to History ---
            function addPurchaseToHistory(productId, productName, productPrice) {
                // Get current date
                const today = new Date();
                const date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
                const historyEntry = `Purchased ${productName} (ID: ${productId}) for ₱${productPrice}`;

                fetch('../../includes/customer_handlers/add_history.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        date: date,
                        history: historyEntry
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error adding purchase to history:', data.error);
                            alert('Error adding purchase to history. Please check console.');
                        } else {
                            console.log('Purchase history updated successfully.');
                            fetchHistory(); // Refresh the history table to show the new purchase
                            alert('Purchase added to history!');
                        }
                    })
                    .catch(error => {
                        console.error('Error adding purchase to history:', error);
                        alert('Error adding purchase to history. Please check console.');
                    });
            }
            // --- End Add Purchase to History ---

            // Fetch and render history
            function fetchHistory() {
                fetch('../../includes/customer_handlers/get_history.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error fetching history:', data.error);
                            renderHistoryTable([{date: "Error", history: "Could not retrieve history"}]);

                        } else {
                            renderHistoryTable(data);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching history:', error);
                        renderHistoryTable([{date: "Error", history: "Could not retrieve history"}]);
                    });
            }

            function renderHistoryTable(history) {
                const tableBody = document.getElementById('history-tbody');
                tableBody.innerHTML = '';
                if(history && history.length > 0){
                    history.forEach(item => {
                        let row = tableBody.insertRow();
                        row.innerHTML = `
                            <td>${item.order_date}</td>
                            <td>${item.order_details}</td>
                        `;
                    });
                }
                else{
                    tableBody.innerHTML = `<tr><td colspan='2'>No purchase history</td></tr>`;
                }

            }


            // for logout functionality
            const logoutLink = document.querySelector('.logout-link');

            logoutLink.addEventListener('click', function() {
                window.location.href = "../../login.php";
            });

            getUsername(); // Call this to display username on page load
            fetchStockProducts(); // Call this to display products on page load
            fetchHistory();
        });
    </script>
</body>
</html>