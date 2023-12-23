<?php
include_once '../models/Cart.php';
include_once '../models/CartItem.php';
include_once 'CartController.php';
include_once '../config/db/db_connection.php';
session_start();

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
    $userId = $_SESSION['user_id'];

    $cart = new Cart($userId);

    $cart->addCartItem($productId, 1);
}

header("Location: ../index.php");
exit();