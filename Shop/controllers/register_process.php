<?php

include_once '../config/db/db_connection.php';
include_once '../models/User.php';

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User(null, $username, $email, $password, null);

    $registrationResult = $user->register($username, $email, $password);

    if ($registrationResult) {

        $userId = getUserId();
        $isAdmin = getIsAdmin();


        $user->setUserId($userId);
        $user->setAdmin($isAdmin);

        header("Location: ../../views/user/login.php");
        exit();
    } else {
        echo "Error during registration.";
        exit();
    }
}


?>



