<?php

include_once '../models/Cart.php';
include_once '../models/CartItem.php';
include_once '../config/db/db_connection.php';

class CartController {

    public function getCart($userId) {

        $conn = connectDB();


        $sql = "SELECT * FROM carts WHERE user_id = $userId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cart = new Cart($row['cart_id'], $row['user_id']);
            $cart->setProducts($this->getCartProducts($row['cart_id'], $conn));
            return $cart;
        }

        $conn->close();

        return null;
    }


    public function addToCart($userId, $productId, $quantity) {

        $conn = connectDB();


        $cart = $this->getCart($userId);


        if ($cart === null) {
            $cart = $this->createCart($userId, $conn);
        }

        $product = $this->getProductById($productId, $conn);

        $cart->addProduct($product, $quantity);

        $this->saveCartToDB($cart, $conn);

        $conn->close();

        return $cart;
    }

    public function removeFromCart($userId, $productId) {
        $conn = connectDB();

        $cart = $this->getCart($userId);

        if ($cart !== null) {
            $cart->removeProduct($productId);

            $this->saveCartToDB($cart, $conn);
        }


        $conn->close();

        return $cart;
    }
    public static function getCartItems($userID, $conn) {

        $cartItems = array();

        $sql = "SELECT * FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $cartItem = new CartItem($row['cart_item_id'], $row['cart_id'], $row['product_id'], $row['quantity']);
            $cartItems[] = $cartItem;
        }

        $stmt->close();

        return $cartItems;
    }

    private function createCart($userId, $conn) {
        $sql = "INSERT INTO carts (user_id) VALUES ($userId)";
        $conn->query($sql);

        $cartId = $conn->insert_id;

        return new Cart($cartId, $userId);
    }

    private function saveCartToDB($cart, $conn) {

        $cartId = $cart->getCartId();
        $userId = $cart->getUserId();

        $sqlDelete = "DELETE FROM cart_items WHERE cart_id = $cartId";
        $conn->query($sqlDelete);


        foreach ($cart->getProducts() as $product) {
            $productId = $product->getProductId();
            $quantity = $cart->getProductQuantity($productId);

            $sqlInsert = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ($cartId, $productId, $quantity)";
            $conn->query($sqlInsert);
        }
    }


    private function getProductById($productId, $conn) {
        $sql = "SELECT * FROM products WHERE product_id = $productId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Product($row['product_id'], $row['name'], $row['description'], $row['price']);
        }

        return null;
    }


    private function getCartProducts($cartId, $conn) {
        $products = array();

        $sql = "SELECT * FROM cart_items WHERE cart_id = $cartId";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $productId = $row['product_id'];
            $product = $this->getProductById($productId, $conn);
            $quantity = $row['quantity'];

            $cartItem = new CartItem($product, $quantity);
            $products[] = $cartItem;
        }

        return $products;
    }
}
