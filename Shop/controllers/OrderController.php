<?php

include_once '../models/Order.php';
include_once '../models/Product.php';
include_once '../config/db/db_connection.php';

class OrderController {

    // Метод для создания заказа
    public function createOrder($userId, $productIds, $quantities) {
        // Подключаемся к базе данных
        $conn = connectDB();

        // Создаем объект заказа
        $order = new Order(null, $userId, date("Y-m-d H:i:s"), 0);

        // Добавляем продукты к заказу
        for ($i = 0; $i < count($productIds); $i++) {
            $product = $this->getProductById($productIds[$i], $conn);
            $order->addProduct($product, $quantities[$i]);
        }

        // Вычисляем общую сумму заказа
        $order->calculateTotalAmount();

        // Сохраняем заказ в базе данных
        $this->saveOrderToDB($order, $conn);

        // Закрываем соединение с базой данных
        $conn->close();

        // Возвращаем объект заказа
        return $order;
    }

    // Метод для получения информации о заказе
    public function getOrderDetails($orderId) {
        // Подключаемся к базе данных
        $conn = connectDB();

        // Ваш код для получения информации о заказе из базы данных
        $sql = "SELECT * FROM orders WHERE order_id = $orderId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $order = new Order($row['order_id'], $row['user_id'], $row['order_date'], $row['total_amount']);
            $order->setProducts($this->getOrderProducts($orderId, $conn));
            return $order;
        }

        // Закрываем соединение с базой данных
        $conn->close();

        return null; // Возвращаем null в случае отсутствия заказа
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

    // Метод для сохранения заказа в базе данных
    private function saveOrderToDB($order, $conn) {
        // Ваш код для сохранения заказа в базе данных
        $userId = $order->getUserId();
        $orderDate = $order->getOrderDate();
        $totalAmount = $order->getTotalAmount();

        $sql = "INSERT INTO orders (user_id, order_date, total_amount) VALUES ($userId, '$orderDate', $totalAmount)";
        $conn->query($sql);

        $orderId = $conn->insert_id;

        // Сохраняем продукты в заказе
        foreach ($order->getProducts() as $product) {
            $productId = $product->getProductId();
            $quantity = $order->getProductQuantity($productId);

            $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ($orderId, $productId, $quantity)";
            $conn->query($sql);
        }
    }

    // Метод для получения продуктов в заказе
    private function getOrderProducts($orderId, $conn) {
        $products = array();

        $sql = "SELECT * FROM order_items WHERE order_id = $orderId";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $productId = $row['product_id'];
            $product = $this->getProductById($productId, $conn);
            $quantity = $row['quantity'];

            $products[] = $product;
        }

        return $products;
    }
}
