<?php

class CartItem
{
    private $cartItemId;
    private $cartId;
    private $productId;
    private $quantity;

    // Конструктор
    public function __construct($cartItemId, $cartId, $productId, $quantity) {
        $this->cartItemId = $cartItemId;
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    // Геттеры и сеттеры

    public function getCartItemId() {
        return $this->cartItemId;
    }

    public function getCartId() {
        return $this->cartId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getQuantity() {
        return $this->quantity;
    }
}