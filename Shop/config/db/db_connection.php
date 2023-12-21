<?php

function connectDB() {
    $servername = "localhost";
    $username = "root"; // Ваше имя пользователя базы данных
    $password = ""; // Ваш пароль базы данных
    $dbname = "shop";

    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Устанавливаем кодировку
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (mysqli_sql_exception $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
