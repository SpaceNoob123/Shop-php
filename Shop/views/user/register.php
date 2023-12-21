<?php

include_once '../../config/db/db_connection.php';
include_once '../../models/User.php';

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверяем уникальность имени пользователя
    if (isUsernameUnique($conn, $username)) {
        // Проверяем уникальность адреса электронной почты
        if (isEmailUnique($conn, $email)) {

            $user = new User(null, $username, $email, $password, null);
            $registrationResult = $user->register($username, $email, $password, $conn);

            if ($registrationResult) {
                // Регистрация успешна, можно добавить дополнительные действия или сообщение об успешной регистрации
                header("Location: login.php");
                exit();
            } else {
                // Произошла ошибка при выполнении запроса
                // Можно добавить сообщение об ошибке или перенаправление на страницу с ошибкой
                echo "Error registering user.";
            }
        } else {
            echo "Email is already in use.";
        }
    } else {
        echo "Username is already taken.";
    }
}

// Функция для проверки уникальности имени пользователя
function isUsernameUnique($conn, $username) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows === 0;
}

// Функция для проверки уникальности адреса электронной почты
function isEmailUnique($conn, $email) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows === 0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="../../template/css/styles.css">
</head>
<body>
<div class="container">
    <h2>Registration</h2>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Register</button>
    </form>

    <?php
    // Показываем сообщение об успешной регистрации, если таковое имеется
    if (isset($registrationResult) && $registrationResult) {
        echo "Registration successful!";
    }
    ?>
</div>
</body>
</html>
