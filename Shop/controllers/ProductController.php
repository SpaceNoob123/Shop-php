<?php

include_once '../models/Product.php';
include_once '../config/db/db_connection.php';

class ProductController {

    public function getProductsByCategory($categoryId) {
        $conn = connectDB();


        $sql = "SELECT * FROM products WHERE category_id = $categoryId";
        $result = $conn->query($sql);

        $products = array();
        while ($row = $result->fetch_assoc()) {
             $product = new Product($row['product_id'], $row['name'], $row['description'], $row['price']);
             $products[] = $product;
        }
        $conn->close();


        return $products;
    }

    public function getProductDetails($productId) {
        $conn = connectDB();


        $sql = "SELECT * FROM products WHERE product_id = $productId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
             $row = $result->fetch_assoc();
             $product = new Product($row['product_id'], $row['name'], $row['description'], $row['price']);
             return $product;
        }


        $conn->close();

        return null;
    }
}