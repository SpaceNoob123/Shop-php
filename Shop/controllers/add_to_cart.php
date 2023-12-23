<?php
include_once '../models/Cart.php';
include_once '../models/CartItem.php';
session_start();
include_once '../config/db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {

        if (isset($_POST['product_id'])) {
            $productId = $_POST['product_id'];


            addToCart($productId);


            header("Location: ../index.php");
            exit();
        }
    }
}

function addToCart($productId) {
    $conn = connectDB();

    $userId = $_SESSION['user_id'];

    $cart = new Cart(null, $userId);


    $cartItem = new CartItem(null, $cart->getCartId(), $productId, 1);

    $cart->addCartItem($cartItem,$conn);
}


header("Location: ../index.php");
exit();
