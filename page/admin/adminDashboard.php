<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockWise| AdminDashboard</title>
    <link rel="stylesheet" href="../../public/main.css">
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
            <li><a href="#" data-target="customers">Customers</a></li>
            <li><a href="#" data-target="sales-reports">Reports</a></li>
            <li><a href="#" data-target="settings">Settings</a></li>
        </ul>

        <div class="logout-container">
            <a href="#" data-target="log-out" class="logout-link">Log Out</a>
        </div>
    </aside>

    <main class="main-container">
        <section id="dashboard" class="active">
            <div class="main-content">
                <h1>Welcome Admin!</h1>
                <p>Click on side tabs to get started.</p>
            </div>
        </section>

        <section id="inventory">
            <div class="main-content">
                <h1>Inventory</h1>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name of Product</th>
                            <th>Price</th>
                            <th>Qnty</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventory-tbody"></tbody>
                </table>
            </div>
        </section>

        <section id="customers">
            <div class="main-content">
                <h1>Customers</h1> 
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name of Customer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customers-tbody"></tbody>
                </table>
            </div>
        </section>

        <section id="sales-reports">
            <div class="main-content">
                <h1>Sales Reports</h1>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Sales Reports</th>
                            <th>Actions</th>
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

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalContent">
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

        // Fetch and render inventory products
        function fetchProducts() {
            fetch('../../includes/admin_handlers/getInventory.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error fetching products:', data.error);
                        return;
                    }
                    renderInventoryTable(data);
                });
        }

        function renderInventoryTable(products) {
            const tableBody = document.getElementById('inventory-tbody');
            tableBody.innerHTML = '';
            products.forEach(product => {
                let row = tableBody.insertRow();
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>$${product.price}</td>
                    <td>${product.quantity}</td>
                    <td>
                        <button class="update-button" data-name="${product.name}" data-price="${product.price}" data-quantity="${product.quantity}">Update</button>
                        <button class="delete-button" data-name="${product.name}">Delete</button>
                    </td>
                `;
            });
            setupInventoryButtons();
        }

        // Setup update and delete buttons for products
        function setupInventoryButtons() {
            const updateButtons = document.querySelectorAll('.update-button');
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
                        fetch('../../includes/admin_handlers/deleteInventory.php', {
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

        // Fetch and render customers
        function fetchCustomers() {
            fetch('../../includes/admin_handlers/getCustomer.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error fetching customers:', data.error);
                        return;
                    }
                    renderCustomerTable(data);
                });
        }

        function renderCustomerTable(customers) {
            const tableBody = document.getElementById('customers-tbody');
            tableBody.innerHTML = '';
            customers.forEach(customer => {
                let row = tableBody.insertRow();
                row.innerHTML = `
                    <td>${customer.name}</td>
                    <td>
                        <button class="update-button" data-name="${customer.name}">Update</button>
                        <button class="delete-button" data-name="${customer.name}">Delete</button>
                    </td>
                `;
            });
            setupCustomerButtons();
        }

        function setupCustomerButtons() {
            const updateButtons = document.querySelectorAll('.update-button');
            const deleteButtons = document.querySelectorAll('.delete-button');

            updateButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const oldName = button.dataset.name;
                    
                    showEditModal(oldName, 'customer'); 
                });
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const name = button.dataset.name;
                    if (confirm(`Are you sure you want to delete this customer?`)) {
                        fetch('../../includes/admin_handlers/deleteCustomer.php', {
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
                                    console.error('Error deleting customer:', data.error);
                                } else {
                                    fetchCustomers();
                                }
                            });
                    }
                });
            });
        }

        //
        const modal = document.getElementById('editModal');
        const modalContent = document.getElementById('modalContent');
        const closeButton = modal.querySelector('.close');

        function renderTable(sectionId, data, tableBodyId) {
            const tableBody = document.getElementById(tableBodyId);
            tableBody.innerHTML = '';

            data.forEach(item => {
                let row = tableBody.insertRow();
                if (sectionId === 'products') {
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>$${item.price}</td>
                        <td>${item.quantity}</td>
                        <td><button class="update-button" data-product-id="${item.id}">Update</button></td>
                    `;
                } else if (sectionId === 'customers') {
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td><button class="update-button" data-customer-id="${item.id}">Update</button></td>
                    `;
                } else {
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.date}</td>
                        <td>${item.report}</td>
                        <td><button class="update-button" data-report-id="${item.id}">Update</button></td>
                    `;
                }
            });

            // for the update button and edit modal
            const updateButtons = document.querySelectorAll('.update-button');

            updateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const itemId = this.dataset.productId || this.dataset.customerId || this.dataset.reportId;
                    const section = this.closest('section');

                    let editContent = `<h2 style="color: #4b515e">Edit `
                                            if (section.id === 'inventory') {
                                                editContent += `Product ${itemId}`;
                                            } else if (section.id === 'customers') {
                                                editContent += `Customer ${itemId}`;
                                            } else {
                                                editContent += `Report ${itemId}`;
                                            }
                                        editContent += `</h2>`;
                    if(section.id === 'inventory'){
                        editContent += `<form>
                                            <input type="text" id="name-input" placeholder="Name of Product" style="padding: 5px 10px; margin-right: 5px">
                                            <input type="number" id="price-input" placeholder="Price" style="padding: 5px 10px; margin-right: 5px">
                                            <input type="number" id="quantity-input" placeholder="Quantity" style="padding: 5px 10px">
                                            <button type="button" id="save-btn" data-item-id="${itemId}" style="margin-top: 20px; padding: 8px 10px; background-color: #4b515e; color: white; border: none; cursor: pointer">Save Changes</button>
                                        </form>`;
                    }else if (section.id === 'customers') {
                        editContent += `<form>
                                            <input type="text" id="customer-name-input" placeholder="Name of Customer" style="padding: 5px 10px">
                                            <button type="button" id="save-btn" data-item-id="${itemId}" style="display: block; margin-top: 20px; padding: 8px 10px; background-color: #4b515e; color: white; border: none; cursor: pointer">Save Changes</button>
                                        </form>`;
                    } else {
                        editContent += `<form>
                                            <input type="text" id="date-input" placeholder="Date" style="padding: 5px 10px; margin-right: 5px">
                                            <input type="text" id="report-input" placeholder="Sales Report" style="padding: 5px 10px;">
                                            <button type="button" id="save-btn" data-item-id="${itemId}" style="display: block; margin-top: 20px; padding: 8px 10px; background-color: #4b515e; color: white; border: none; cursor: pointer">Save Changes</button>
                                        </form>`;
                    }

                    modalContent.innerHTML = editContent; 
                    modal.style.display = "block"; 

                    // for save button functionality
                    const saveButton = document.getElementById('save-btn');

                    saveButton.addEventListener('click', () => {
                        const modalSection = section;
                        const itemId = saveButton.dataset.itemId;

                        if (modalSection.id === 'inventory') {
                            const newName = document.getElementById('name-input').value;
                            const newPrice = document.getElementById('price-input').value;
                            const newQuantity = document.getElementById('quantity-input').value;

                            products = products.map(item => item.id == itemId ? { ...item, name: newName, price: newPrice, quantity: newQuantity } : item);
                        } else if (modalSection.id === 'customers') {
                            const newName = document.getElementById('customer-name-input').value;

                            customers = customers.map(item => item.id == itemId ? { ...item, name: newName } : item);
                        } else {
                            const newDate = document.getElementById('date-input').value;
                            const newReport = document.getElementById('report-input').value;

                            reports = reports.map(item => item.id == itemId ? { ...item, date: newDate, report: newReport } : item);
                        }

                        renderTables();
                        modal.style.display = 'none';
                    });
                });
            });
        };

        renderTables();
            
        // closing the edit modal
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
    });
</script>
</html>