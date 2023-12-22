<?php
include_once '../config/db/db_connection.php';
include_once '../models/Cart.php';
include_once '../models/CartItem.php';

$conn = connectDB();
session_start();


$userId = $_SESSION['user_id'];

$sql = "SELECT * FROM carts WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = new Cart(null,$userId);

    while ($row = $result->fetch_assoc()) {
        $cartItem = new CartItem($row['cart_id'], $row['user_id'], $row['product_id'], $row['quantity']);
        $cart->addCartItem($cartItem,$conn);
    }

    $stmt->close();
} else {
    die("Error in preparing the SQL statement.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="../template/css/main.css">
</head>
<body>
<header>
    <div class="navigation">
        <h1>Shopping Cart</h1>
        <div class="user-actions">
            <a href="logout.php">Logout</a>
            <a href="../index.php">Back</a>
        </div>
    </div>
</header>

<main>
    <div class="cart-items">
        <?php
        foreach ($cart->getCartItems($conn) as $cartItem) {
            echo '<div class="cart-item">';
            echo '<p>Product ID: ' . $cartItem->getProductId() . '</p>';
            echo '<p>Quantity: ' . $cartItem->getQuantity() . '</p>';
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