<?php

class Product
{
    private $productId;
    private $name;
    private $description;
    private $price;

    // Конструктор
    public function __construct($name, $description, $price) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    // Геттеры и сеттеры

    public function getProductId() {
        return $this->productId;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }
}