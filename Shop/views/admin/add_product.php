<?php

include_once '../../config/db/db_connection.php';
include_once '../../models/Product.php';

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Подготавливаем SQL-запрос для вставки продукта в базу данных
    $sql = "INSERT INTO products (name, description, price) VALUES (?, ?, ?)";

    // Подготавливаем выражение для выполнения
    $stmt = $conn->prepare($sql);

    // Проверяем, успешно ли подготовлено выражение
    if ($stmt) {
        // Привязываем параметры
        $stmt->bind_param("ssd", $name, $description, $price);

        // Выполняем запрос
        $result = $stmt->execute();

        // Проверяем успешность выполнения запроса
        if ($result) {
            // Продукт успешно добавлен
            // Можно добавить дополнительные действия или сообщение об успешном добавлении
            header("Location: admin_panel.php");
            exit();
        } else {
            // Произошла ошибка при выполнении запроса
            // Можно добавить сообщение об ошибке или перенаправление на страницу с ошибкой
            echo "Error executing SQL query.";
        }

        // Закрываем выражение
        $stmt->close();
    } else {
        // Произошла ошибка при подготовке выражения
        // Можно добавить сообщение об ошибке или перенаправление на страницу с ошибкой
        echo "Error preparing SQL statement.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="../../template/css/admin.css">
</head>
<body>
<header>
    <h1>Add Product</h1>
</header>

<main>
    <form method="post" action="">
        <!-- Add form fields for product details -->
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <!-- Add other fields as needed -->

        <button type="submit">Add Product</button>
    </form>
</main>

<footer>
    <a href="admin_panel.php">Back to Admin Panel</a>
</footer>
</body>
</html>
