<?php
session_start();
$user=$_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple E-commerce Site</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
        .banner {
            background-image: url('banner.jpg');
            background-size: cover;
            background-position: center;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
            font-size: 2em;
            text-align: center;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }
        .product {
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 10px;
            width: calc(100% - 40px);
            max-width: 200px;
            text-align: center;
            box-sizing: border-box;
        }
        .product img {
            max-width: 100%;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        button{
            width: 150px;
            height: 150px;
            border-radius: 20%;
            position: relative;
            left: 50%;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        @media (min-width: 600px) {
            .product {
                width: calc(50% - 40px);
            }
        }
        @media (min-width: 900px) {
            .product {
                width: calc(25% - 40px);
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Simple E-commerce Site</h1>
    </header>
    <nav>
        <a href="#home">Home</a>
        <a href="#shop">Shop</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
    </nav>
    <div class="banner">
        Welcome to Our Store!
    </div>
    <div>
        <button onclick="login()">login</button>
    </div>
    
    <script>

async function login() {
    <?php
      if (empty($user)==true): ?>
     window.location.href="http://localhost/website/shop/pages/login/";
     <?php endif; ?>
      }
    </script>
</body>
</html>
