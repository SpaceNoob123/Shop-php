<?php

class Order
{
    private $orderId;
    private $userId;
    private $orderDate;
    private $totalAmount;
    private $products = array();

    public function __construct($orderId, $userId, $orderDate, $totalAmount) {
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->orderDate = $orderDate;
        $this->totalAmount = $totalAmount;
        $this->products = array();
    }

    public function calculateTotalAmount() {
        $totalAmount = 0;

        foreach ($this->getProducts() as $product) {
            $totalAmount += $product->getPrice() * $this->getProductQuantity($product->getProductId());
        }

        $this->setTotalAmount($totalAmount);
    }

    // Метод для добавления продукта в заказ
    public function addProduct($product, $quantity) {
        $productId = $product->getProductId();

        // Если продукт уже есть в заказе, обновляем количество
        if (array_key_exists($productId, $this->products)) {
            $this->products[$productId] += $quantity;
        } else {
            // Иначе, добавляем новый продукт в заказ
            $this->products[$productId] = $quantity;
        }
    }

    // Метод для получения продуктов в заказе
    public function getProducts() {
        return $this->products;
    }

    // Метод для получения количества продукта в заказе
    public function getProductQuantity($productId) {
        return isset($this->products[$productId]) ? $this->products[$productId] : 0;
    }

    // Метод для установки общей суммы заказа
    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
    }
    // Метод для установки продуктов в заказе
    public function setProducts($products) {
        $this->products = $products;
    }
    public function getOrderId() {
        return $this->orderId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function getTotalAmount() {
        return $this->totalAmount;
    }
}