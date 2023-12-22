<?php


class Cart
{
    private $cartId;
    private $userId;

    // Constructor
    public function __construct($cartId, $userId)
    {
        $this->cartId = $cartId;
        $this->userId = $userId;
    }

    // Getter methods
    public function getCartId()
    {
        return $this->cartId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function addCartItem($cartItem, $conn)
    {
        $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");


        $cartId = $this->cartId;
        $stmt->bind_param("iii", $cartId, $cartItem->getProductId(), $cartItem->getQuantity());

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getCartItems($conn)
    {
        $cartItems = array();


        $stmt = $conn->prepare("SELECT * FROM cart_items WHERE cart_id = ?");
        $stmt->bind_param("i", $this->cartId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $cartItem = new CartItem($row['cart_item_id'], $row['cart_id'], $row['product_id'], $row['quantity']);
            $cartItems[] = $cartItem;
        }

        return $cartItems;
    }
}