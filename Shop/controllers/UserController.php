<?php

include_once '../models/User.php';
include_once '../config/db/db_connection.php';

class UserController {

    public function registerUser($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User(null, $username, $email, $hashedPassword, false); // Предположим, что при регистрации пользователь не является администратором

        $conn = connectDB();

        $sql = "INSERT INTO users (username, email, password, is_admin) VALUES ('$username', '$email', '$hashedPassword', false)";
        $conn->query($sql);

        $conn->close();

        return $user;
    }

    public function authenticateUser($email, $password) {
        $conn = connectDB();


         $sql = "SELECT * FROM users WHERE email = '$email'";
         $result = $conn->query($sql);

         if ($result->num_rows > 0) {
             $row = $result->fetch_assoc();
             $hashedPasswordFromDB = $row['password'];

             if (password_verify($password, $hashedPasswordFromDB)) {

                 $user = new User($row['user_id'], $row['username'], $row['email'], $row['password'], $row['is_admin']);
                 return $user;
             }
         }

        $conn->close();

        return null;
    }


    public function getUserDetails($userId) {

        $conn = connectDB();


         $sql = "SELECT * FROM users WHERE user_id = $userId";
         $result = $conn->query($sql);

         if ($result->num_rows > 0) {
             $row = $result->fetch_assoc();
             $user = new User($row['user_id'], $row['username'], $row['email'], $row['password'], $row['is_admin']);
             return $user;
         }


        $conn->close();

        return null;
    }
}