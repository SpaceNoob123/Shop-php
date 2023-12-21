<?php

include_once 'models/Product.php';
include_once 'config/db/db_connection.php';
include_once 'models/User.php';

$conn = connectDB();
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product = new Product($row['product_id'], $row['name'], $row['description'], $row['price']);
        $products[] = $product;
    }
}

// Пример: начало HTML-кода
session_start();

$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];
$adminStatus = isset($_SESSION['adminStatus']) && $_SESSION['adminStatus'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Shop</title>
    <link rel="stylesheet" type="text/css" href="template/css/main.css">
</head>
<body>
<header>
    <div class="navigation">
        <h1>Online Shop</h1>
        <div class="user-actions">
            <?php
            if ($loggedIn) {
                echo '<a href="logout.php">Logout</a>';
                echo '<a href="cart.php">View Cart</a>';
                if ($adminStatus) {
                    echo '<a href="views/admin/admin_panel.php">Admin Panel</a>';
                }
            } else {
                echo '<a href="views/user/login.php">Login</a>';
                echo '<a href="views/user/register.php">Register</a>';
            }
            ?>
        </div>
    </div>
</header>

<main>
    <div class="product-list">
        <?php
        foreach ($products as $product) {
            echo '<div class="product">';
            echo '<h2>' . $product->getName() . '</h2>';
            echo '<p>' . $product->getDescription() . '</p>';
            echo '<p>Price: $' . $product->getPrice() . '</p>';
            echo '<button>Add to Cart</button>';
            echo '</div>';
        }
        ?>
    </div>
</main>

<footer>
    <p>&copy; 2023 Online Shop. All rights reserved.</p>
</footer>
</body>
</html>