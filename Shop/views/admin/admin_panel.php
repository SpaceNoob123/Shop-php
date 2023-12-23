<?php
// admin_panel.php

include_once '../../config/db/db_connection.php';
include_once '../../models/User.php';
include_once '../../models/Product.php';

$conn = connectDB();


$users = User::getAllUsers($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="../../template/css/admin.css">
</head>
<body>
<header>
    <h1>Admin Panel</h1>
    <div class="admin-actions">
        <a href="user_list.php">Список пользователей</a>
        <a href="add_product.php">Добавить товар</a>
        <a href="delete_product.php">Удалить товар</a>
    </div>
</header>

<main>
    <h2>Список пользователей</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user->getUserId(); ?></td>
                <td><?= $user->getUsername(); ?></td>
                <td><?= $user->getEmail(); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<footer>
    <a href="../../index.php">На главную</a>
</footer>
</body>
</html>
