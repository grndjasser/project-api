<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<style>
    /* Add your CSS styling here */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .profile-container {
        margin: 20px;
    }
    .order-history {
        margin-top: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    header {
        background-color: #333;
        color: #fff;
        padding: 10px;
        text-align: center;
    }
    nav {
        display: flex;
        justify-content: center;
        background-color: #444;
        flex-wrap: wrap;
    }
    nav a {
        color: white;
        padding: 14px;
        text-decoration: none;
        text-align: center;
    }
    nav a:hover {
        background-color: #555;
    }
</style>
</head>
<body>
    <nav>
        <a href="#home">Home</a>
        <a href="#shop">Shop</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
        <button onclick="logout()">Logout</button>
    </nav>

<div class="profile-container">
    <!-- User Details -->
    <h2>User Profile</h2>
    <p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <p id="user-id">ID: <?php echo htmlspecialchars($_SESSION['id']); ?></p>
    <div class="login-container">
        <h2>Add Product</h2>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Price</label>
        <input type="text" id="price" name="price" required>

        <label for="description">Description</label>
        <input type="text" id="description" name="description" required>

        <label for="seller_id">Your ID</label>
        <input type="text" id="seller_id" name="seller_id" required readonly>

        <button onclick="addProduct()">Add</button>
    </div>
   
    <div class="order-history">
        <h3>Order History</h3>
        <table id="orderTable">
            <tr>
                <th>Order ID</th>
                <th>seller_id</th>
                <th>price</th>
            </tr>
            <!-- Order history data will be populated here -->
        </table>
        <h3>Products</h3>
        <table id="productTable">
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <!-- Products data will be populated here -->
        </table>
    </div>
</div>
<script>
    async function addProduct() {
        const url = 'http://localhost/website/shop/api/product.php';
        const name = document.getElementById('name').value;
        const price = document.getElementById('price').value;
        const description = document.getElementById('description').value;
        const seller_id = document.getElementById('seller_id').value;

        const data = {
            "product_name": name,
            "price": price,
            "description": description,
            "seller_id": seller_id
        };

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.status === true) {
                window.alert('Product added');
                fetchProfileData(); // Refresh data after adding a product
            } else {
                window.alert('Error adding product: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            window.alert('Error adding product');
        }
    }

    function logout() {
        <?php session_destroy(); ?>
        window.location.href = 'http://localhost/website/shop/pages/home/';
    }

    async function fetchProfileData() {
        const url = 'http://localhost/website/shop/api/fetch_profile.php';
        try {
            const response = await fetch(url);

            if (!response.ok) {
                const text = await response.text();
                console.error('Error:', text);
                window.alert('Error fetching profile data: ' + text);
                if (response.status === 401) {
                    // Redirect to login page if unauthorized
                    window.location.href = 'http://localhost/website/shop/pages/login/';
                }
                return;
            }

            const data = await response.json();
            console.log(data);

            // Update order history table
            const orderTable = document.getElementById('orderTable');
            orderTable.innerHTML = `
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                </tr>
            `;
            data.orders.forEach(order => {
                const row = orderTable.insertRow();
                const cell1 = row.insertCell(0);
                const cell2 = row.insertCell(1);
                const cell3 = row.insertCell(2);
                cell1.textContent = order.order_id;
                cell2.textContent = order.seller_id;
                cell3.textContent = order.price;
            });

            // Update products table
            const productTable = document.getElementById('productTable');
            productTable.innerHTML = `
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            `;
            data.products.forEach(product => {
                const row = productTable.insertRow();
                const cell1 = row.insertCell(0);
                const cell2 = row.insertCell(1);
                const cell3 = row.insertCell(2);
                const cell4 = row.insertCell(3);
                cell1.textContent = product.product_name;
                cell2.textContent = product.price;
                cell3.textContent = product.description;
                const orderButton = document.createElement('button');
                orderButton.textContent = 'Order';
                orderButton.onclick = function() {
                    order(product);
                };
                cell4.appendChild(orderButton);
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function order(product) {
        const url = 'http://localhost/website/shop/api/order.php';
        const dataToSend = {
            "product_id": product.product_id,
            "price": product.price,
            "seller_id": product.seller_id
        };
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(dataToSend)
            });
            
            const data = await response.json();
            console.log(data);
            // Handle success or further actions after ordering
        } catch (error) {
            console.error('Error ordering product:', error);
        }
    }

    window.onload = function() {
        fetchProfileData();
        const userId = document.getElementById('user-id').textContent.split(': ')[1];
        document.getElementById('seller_id').value = userId;
    }
</script>
</body>
</html>
