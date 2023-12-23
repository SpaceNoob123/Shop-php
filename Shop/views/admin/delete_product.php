<?php
include_once '../../config/db/db_connection.php';
include_once '../../models/Product.php';
include_once '../../controllers/ProductController.php';
$conn = connectDB();

$sql = "SELECT product_id, name FROM products";
$result = $conn->query($sql);

$products = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];

    if (!empty($productId)) {
        $deleteSql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($deleteSql);

        if ($stmt) {
            $stmt->bind_param("i", $productId);
            $stmt->execute();

            header("Location: admin_panel.php");
            exit();
        } else {
            echo "Error preparing SQL statement for deletion.";
        }
    } else {

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
        <label for="product_id">Select Product to Delete:</label>
        <select id="product_id" name="product_id">
            <?php
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
