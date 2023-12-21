<?php

class Cart
{
    private $cartId;
    private $userId;

    // Конструктор
    public function __construct($cartId, $userId) {
        $this->cartId = $cartId;
        $this->userId = $userId;
    }

    // Геттеры и сеттеры

    public function getCartId() {
        return $this->cartId;
    }

    public function getUserId() {
        return $this->userId;
    }
}