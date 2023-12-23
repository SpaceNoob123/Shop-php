<?php
include_once '../models/Cart.php';
include_once '../config/db/db_connection.php';
include_once '../models/CartItem.php';
session_start();
$conn = connectDB();
$userId = $_SESSION['user_id'];

$cart = new Cart($userId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your head content here -->
</head>
<body>
<header>
    <div class="navigation">
        <h1>Online Shop</h1>
        <div class="user-actions">
            <?php
            echo '<a href="../index.php">Logout</a>';
            ?>
        </div>
    </div>
</header>

<main>
    <div class="cart-items">
        <?php
        foreach ($cart->getCartItems() as $cartItem) {
            echo '<div class="cart-item">';
            echo '<p>Product ID: ' . $cartItem['product_id'] . '</p>';
            echo '<p>Quantity: ' . $cartItem['quantity'] . '</p>';
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