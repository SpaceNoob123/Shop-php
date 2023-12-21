<?php
// user_list.php

include_once '../../config/db/db_connection.php';
include_once '../../models/User.php';

$conn = connectDB();

$users = User::getAllUsers($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" type="text/css" href="../../template/css/admin.css">
</head>
<body>
<header>
    <h1>User List</h1>
</header>

<main>
    <h2>List of Users</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <!-- Add other fields as needed -->
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user->getUserId(); ?></td>
                <td><?= $user->getUsername(); ?></td>
                <td><?= $user->getEmail(); ?></td>
                <!-- Add other fields as needed -->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<footer>
    <a href="admin_panel.php">Back to Admin Panel</a>
</footer>
</body>
</html>
