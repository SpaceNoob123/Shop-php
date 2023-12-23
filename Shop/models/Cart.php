<?php


class Cart
{
    private $userId;
    private $items = array();

    public function __construct($userId) {
        $this->userId = $userId;
        $this->loadFromJSON(); // Load existing cart data
    }

    public function addCartItem($productId, $quantity) {
        $item = array(
            'product_id' => $productId,
            'quantity' => $quantity
        );

        $this->items[] = $item;
        $this->saveToJSON();
    }

    public function getCartItems() {
        return $this->items;
    }

    private function saveToJSON() {
        $jsonFile = __DIR__ . '/cart_' . $this->userId . '.json';
        file_put_contents($jsonFile, json_encode($this->items));
    }

    private function loadFromJSON() {
        $jsonFile = __DIR__ . '/cart_' . $this->userId . '.json';

        if (file_exists($jsonFile)) {
            $jsonContents = file_get_contents($jsonFile);
            $this->items = json_decode($jsonContents, true);
        }
    }
}