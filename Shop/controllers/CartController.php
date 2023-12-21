<?php

include_once '../models/Cart.php';
include_once '../models/CartItem.php';
include_once '../config/db/db_connection.php';

class CartController {

    // Метод для получения корзины пользователя
    public function getCart($userId) {
        // Подключаемся к базе данных
        $conn = connectDB();

        // Ваш код для получения корзины из базы данных
        $sql = "SELECT * FROM carts WHERE user_id = $userId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cart = new Cart($row['cart_id'], $row['user_id']);
            $cart->setProducts($this->getCartProducts($row['cart_id'], $conn));
            return $cart;
        }

        // Закрываем соединение с базой данных
        $conn->close();

        return null; // Возвращаем null в случае отсутствия корзины
    }

    // Метод для добавления продукта в корзину
    public function addToCart($userId, $productId, $quantity) {
        // Подключаемся к базе данных
        $conn = connectDB();

        // Получаем корзину пользователя
        $cart = $this->getCart($userId);

        // Если у пользователя еще нет корзины, создаем новую
        if ($cart === null) {
            $cart = $this->createCart($userId, $conn);
        }

        // Получаем продукт
        $product = $this->getProductById($productId, $conn);

        // Добавляем продукт в корзину
        $cart->addProduct($product, $quantity);

        // Сохраняем обновленную корзину в базе данных
        $this->saveCartToDB($cart, $conn);

        // Закрываем соединение с базой данных
        $conn->close();

        return $cart;
    }

    // Метод для удаления продукта из корзины
    public function removeFromCart($userId, $productId) {
        // Подключаемся к базе данных
        $conn = connectDB();

        // Получаем корзину пользователя
        $cart = $this->getCart($userId);

        if ($cart !== null) {
            // Удаляем продукт из корзины
            $cart->removeProduct($productId);

            // Сохраняем обновленную корзину в базе данных
            $this->saveCartToDB($cart, $conn);
        }

        // Закрываем соединение с базой данных
        $conn->close();

        return $cart;
    }

    // Метод для создания корзины
    private function createCart($userId, $conn) {
        $sql = "INSERT INTO carts (user_id) VALUES ($userId)";
        $conn->query($sql);

        $cartId = $conn->insert_id;

        return new Cart($cartId, $userId);
    }

    // Метод для сохранения корзины в базе данных
    private function saveCartToDB($cart, $conn) {
        // Ваш код для сохранения корзины в базе данных
        $cartId = $cart->getCartId();
        $userId = $cart->getUserId();

        // Удаляем существующие записи о продуктах в корзине
        $sqlDelete = "DELETE FROM cart_items WHERE cart_id = $cartId";
        $conn->query($sqlDelete);

        // Добавляем обновленные записи о продуктах в корзине
        foreach ($cart->getProducts() as $product) {
            $productId = $product->getProductId();
            $quantity = $cart->getProductQuantity($productId);

            $sqlInsert = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ($cartId, $productId, $quantity)";
            $conn->query($sqlInsert);
        }
    }

    // Метод для получения продукта по его идентификатору
    private function getProductById($productId, $conn) {
        $sql = "SELECT * FROM products WHERE product_id = $productId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Product($row['product_id'], $row['name'], $row['description'], $row['price']);
        }

        return null; // Возвращаем null в случае отсутствия продукта
    }

    // Метод для получения продуктов в корзине
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
