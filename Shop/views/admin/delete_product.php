<?php
// delete_product.php

include_once '../../config/db/db_connection.php';
include_once '../../models/Product.php';

$conn = connectDB();

// Получаем список продуктов из базы данных
$sql = "SELECT product_id, name FROM products";
$result = $conn->query($sql);

$products = [];

if ($result) {
    // Преобразуем результат в ассоциативный массив
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка отправки формы удаления продукта
    $productId = $_POST['product_id'];

    // Проверка, что выбран продукт
    if (!empty($productId)) {
        // Удаление продукта из базы данных
        $deleteSql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($deleteSql);

        if ($stmt) {
            $stmt->bind_param("i", $productId);
            $stmt->execute();

            // После удаления продукта, перенаправляем на страницу администратора
            header("Location: admin_panel.php");
            exit();
        } else {
            // Ошибка подготовки выражения для удаления
            echo "Error preparing SQL statement for deletion.";
        }
    } else {
        // Продукт не выбран
        echo "Please select a product to delete.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <link rel="stylesheet" type="text/css" href="../../template/css/admin.css">
</head>
<body>
<header>
    <h1>Delete Product</h1>
</header>

<main>
    <form method="post" action="">
        <!-- Добавляем выпадающий список для выбора продукта -->
        <label for="product_id">Select Product to Delete:</label>
        <select id="product_id" name="product_id">
            <?php
            // Добавляем опции на основе продуктов из базы данных
            foreach ($products as $product) {
                echo "<option value='{$product['product_id']}'>{$product['name']}</option>";
            }
            ?>
        </select>

        <button type="submit">Delete Product</button>
    </form>
</main>

<footer>
    <a href="admin_panel.php">Back to Admin Panel</a>
</footer>
</body>
</html>
