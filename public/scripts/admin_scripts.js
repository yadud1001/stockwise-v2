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

    // --- Get User Data (including Role) and Update Welcome Message ---
    function getUserData() { // Changed function name to be more accurate
    const welcomeMessageElement = document.getElementById('welcome-message');
    if (!welcomeMessageElement) {
        console.error("Welcome message element not found!");
        return;
    }

    fetch('../../includes/fetch_handlers/get_username.php') // Your PHP script now returns role as well
        .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
        })
        .then(data => {
        if (data.error) {
            welcomeMessageElement.textContent = `Welcome Guest!`; 
        } else {
            welcomeMessageElement.textContent = `Welcome ${data.username}!`;
        }
        })
        .catch(error => {
        console.error('Error fetching user data:', error);
        welcomeMessageElement.textContent = `Welcome Guest!`;
        });
    }
    // --- End Get User Data ---

    // --- Fetch Inventory Data and Display ---
    function fetchInventoryData() {
        const inventoryTableBody = document.getElementById('inventory-tbody');
        if (!inventoryTableBody) {
            console.error("Inventory table body not found!");
            return;
        }

        fetch('../../includes/fetch_handlers/get_stock_products.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    inventoryTableBody.innerHTML = `<tr><td colspan="3" class="error-message">${data.error}</td></tr>`;
                } else {
                    let html = '';
                    data.forEach(product => {
                        html += `
                            <tr>
                                <td>${product.name}</td>
                                <td>${product.price}</td>
                                <td>${product.stock_quantity}</td>
                            </tr>
                        `;
                    });
                    inventoryTableBody.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error fetching inventory data:', error);
                inventoryTableBody.innerHTML = `<tr><td colspan="3" class="error-message">Failed to fetch inventory data. Please check your connection.</td></tr>`;
            });
    }
    // --- End Fetch Inventory Data and Display ---

    // --- Fetch Sales Reports Data and Display ---
    function fetchSalesReportsData() {
        const reportsTableBody = document.getElementById('reports-tbody');
        if (!reportsTableBody) {
            console.error("Reports table body not found!");
            return;
        }

        fetch('../../includes/fetch_handlers/get_history.php') // Use your get_history.php file
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    reportsTableBody.innerHTML = `<tr><td colspan="3" class="error-message">${data.error}</td></tr>`;
                } else {
                    let html = '';
                    data.forEach(order => {
                        html += `
                            <tr>
                                <td>${order.order_date}</td>
                                <td>${order.customer_username}</td> 
                                <td>${order.order_details}</td> 
                            </tr>
                        `;
                    });
                    reportsTableBody.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error fetching sales reports data:', error);
                reportsTableBody.innerHTML = `<tr><td colspan="3" class="error-message">Failed to fetch sales reports data. Please check your connection.</td></tr>`;
            });
    }
    // --- End Fetch Sales Reports Data and Display ---

    // for logout functionality
    const logoutLink = document.querySelector('.logout-link');

    logoutLink.addEventListener('click', function() {
        window.location.href = "../../login.php";
    });

    getUserData();
    fetchInventoryData();
    fetchSalesReportsData();
});