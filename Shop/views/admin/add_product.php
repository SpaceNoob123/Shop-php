<?php

include_once '../../config/db/db_connection.php';
include_once '../../models/Product.php';
$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];


    $sql = "INSERT INTO products (name, description, price) VALUES (?, ?, ?)";


    $stmt = $conn->prepare($sql);


    if ($stmt) {
        $stmt->bind_param("ssd", $name, $description, $price);

        $result = $stmt->execute();


        if ($result) {

            header("Location: admin_panel.php");
            exit();
        } else {
            echo "Error executing SQL query.";
        }


        $stmt->close();
    } else {
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
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>



        <button type="submit">Add Product</button>
    </form>
</main>

<footer>
    <a href="admin_panel.php">Back to Admin Panel</a>
</footer>
</body>
</html>
