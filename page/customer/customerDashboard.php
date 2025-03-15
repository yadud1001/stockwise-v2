<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockWise| CustomerDashboard</title>
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
                <h1>Welcome Customer!</h1>
                <p>Click on side tabs to get started.</p>
            </div>
        </section>

        <section id="products">
            <div class="main-content">
                <h1>Products Purchased</h1>

                <button id="addProductButton" class="add-button">Add Product</button>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name of Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-tbody"></tbody>
                </table>
            </div>
        </section>

        <section id="history">
            <div class="main-content">
                <h1>Order History</h1>

                <button id="addHistoryButton" class="add-button">Add History</button>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order History</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="history-tbody"></tbody>
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

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalContent">
            </div>
        </div>
    </div>
</body>

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

        // Fetch and render products
        function fetchProducts() {
            fetch('../../includes/customer_handlers/getProduct.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error fetching products:', data.error);
                        return;
                    }
                    renderProductTable(data);
                });
        }

        function renderProductTable(products) {
            const tableBody = document.getElementById('product-tbody');
            tableBody.innerHTML = '';
            products.forEach(product => {
                let row = tableBody.insertRow();
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>$${product.price}</td>
                    <td>${product.quantity}</td>
                    <td>
                        <button class="product-update-button" data-name="${product.name}" data-price="${product.price}" data-quantity="${product.quantity}">Update</button>
                        <button class="delete-button" data-name="${product.name}">Delete</button>
                    </td>
                `;
            });
            setupProductButtons();
        }

        // Setup update and delete buttons for products
        function setupProductButtons() {
            const updateButtons = document.querySelectorAll('.product-update-button');
            const deleteButtons = document.querySelectorAll('.delete-button');

            updateButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const oldName = button.dataset.name;
                    const price = button.dataset.price;
                    const quantity = button.dataset.quantity;
                    showEditModal(oldName, price, quantity, 'product'); 
                });
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const name = button.dataset.name;
                    if (confirm(`Are you sure you want to delete this product?`)) {
                        fetch('../../includes/customer_handlers/deleteProduct.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    name: name
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    console.error('Error deleting product:', data.error);
                                } else {
                                    fetchProducts();
                                }
                            });
                    }
                });
            });
        }

        // Fetch and render history
        function fetchHistory() {
            fetch('../../includes/customer_handlers/getHistory.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error fetching history:', data.error);
                        return;
                    }
                    renderHistoryTable(data);
                });
        }

        function renderHistoryTable(history) {
            const tableBody = document.getElementById('history-tbody');
            tableBody.innerHTML = '';
            history.forEach(item => {
                let row = tableBody.insertRow();
                row.innerHTML = `
                    <td>${item.date}</td>
                    <td>${item.history}</td>
                    <td>
                        <button class="history-update-button" data-id="${item.id}" data-date="${item.date}" data-history="${item.history}">Update</button>
                        <button class="delete-button" data-id="${item.id}">Delete</button>
                    </td>
                `;
            });
            setupHistoryButtons();
        }

        // Setup update and delete buttons for history
        function setupHistoryButtons() {
            const updateButtons = document.querySelectorAll('.history-update-button');
            const deleteButtons = document.querySelectorAll('.delete-button');

            updateButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    const date = button.dataset.date;
                    const history = button.dataset.history;

                    showEditModal(date, history, id, 'history');
                });
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    if (confirm(`Are you sure you want to delete this order history?`)) {
                        fetch('../../includes/customer_handlers/deleteHistory.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id: id
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    console.error('Error deleting history:', data.error);
                                } else {
                                    fetchHistory();
                                }
                            });
                    }
                });
            });
        }

        // Modal Functionality
        const modal = document.getElementById('editModal');
        const modalContent = document.getElementById('modalContent');
        const closeButton = modal.querySelector('.close');

        function showEditModal(data1 = '', data2 = '', data3 = '', type) {            
            let modalTitle = type === 'product' ? 'Product' : 'History';
            let label1 = type === 'product' ? 'Name of Product' : 'Date';
            let label2 = type === 'product' ? 'Price' : 'History';
            let inputType2 = type === 'product' ? 'number' : 'text'; 
            let dataName = type === 'product' ? 'name-input' : 'date-input';
            let dataPrice = type === 'product' ? 'price-input' : 'history-input';
            let dataId = type === 'history' ? data3 : '';

            let editContent = `<h2 style="color: #4b515e">${data1 ? 'Edit ' + modalTitle : 'Add ' + modalTitle}</h2>`;
            editContent += `<form id="${type}Form">
                <label for="${dataName}" style="color: #4b515e">${label1}: </label>
                <input type="text" id="${dataName}" placeholder="${label1}" value="${data1}" style="padding: 5px 10px; margin-right: 5px" required>
                <label for="${dataPrice}" style="color: #4b515e">${label2}: </label>
                <input type="${inputType2}" id="${dataPrice}" placeholder="${label2}" value="${data2}" style="padding: 5px 10px; margin-right: 5px" required>
                ${type === 'product' ? `<div style="margin-top: 10px; display: flex; justify-content: center; align-items: center"><label for="quantity-input" style="color: #4b515e; margin-right: 5px">Quantity: </label><input type="number" id="quantity-input" placeholder="Quantity" value="${data3}" style="padding: 5px 10px" required></div>` : ''}
                <button type="submit" id="save-btn" style="display: block; margin-top: 20px; padding: 8px 10px; background-color: #4b515e; color: white; border: none; cursor: pointer">${data1 ? 'Save Changes' : 'Add ' + modalTitle}</button>
            </form>`;

            modalContent.innerHTML = editContent;
            modal.style.display = "block";

            const form = document.getElementById(`${type}Form`);
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                let newData1 = document.getElementById(`${dataName}`).value;
                let newData2 = document.getElementById(`${dataPrice}`).value;
                let newData3 = type === 'product' ? document.getElementById('quantity-input').value : dataId;

                let url = data1 ? `../../includes/customer_handlers/update${modalTitle}.php` : `../../includes/customer_handlers/add${modalTitle}.php`;
                let data = {};

                if (type === 'product') {
                    data = data1 ? {
                        oldName: data1,
                        newName: newData1,
                        price: newData2,
                        quantity: newData3
                    } : {
                        name: newData1,
                        price: newData2,
                        quantity: newData3
                    };
                } else {
                    data = data1 ? {
                        id: dataId,
                        date: newData1,
                        history: newData2
                    } : {
                        date: newData1,
                        history: newData2
                    };
                }

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error saving ' + modalTitle + ':', data.error);
                        } else {
                            if (type === 'product') {
                                fetchProducts();
                            } else {
                                fetchHistory();
                            }
                            modal.style.display = 'none';
                        }
                    });
            });
        }

        // Add Product Button
        document.getElementById('addProductButton').addEventListener('click', () => {
            showEditModal('', '', '', 'product');
        });

        // Add History Button
        document.getElementById('addHistoryButton').addEventListener('click', () => {
            showEditModal('', '', '', 'history');
        });

        // Closing the edit modal
        closeButton.addEventListener('click', () => {
            modal.style.display = "none";
        });

        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });

        // for logout functionality
        const logoutLink = document.querySelector('.logout-link');

        logoutLink.addEventListener('click', function() {
            window.location.href = "../../login.php";
        });

        fetchProducts();
        fetchHistory();
    });
</script>
</html>