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


    public function addProduct($product, $quantity) {
        $productId = $product->getProductId();


        if (array_key_exists($productId, $this->products)) {
            $this->products[$productId] += $quantity;
        } else {

            $this->products[$productId] = $quantity;
        }
    }


    public function getProducts() {
        return $this->products;
    }


    public function getProductQuantity($productId) {
        return isset($this->products[$productId]) ? $this->products[$productId] : 0;
    }


    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
    }

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