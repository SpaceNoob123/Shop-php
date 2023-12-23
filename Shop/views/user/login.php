<!-- login.php -->

<?php
include_once '../../config/db/db_connection.php';
include_once '../../models/User.php';

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['adminStatus'] = false;
    $user = new User(null, null, $email, $password, null);
    $loginResult = $user->login($email, $password, $conn);
    if ($loginResult) {
        if($email == "adminadmin@gmail.com"){
            session_start();
            $_SESSION['user_id'] = $user->getUserId();
            $_SESSION['loggedIn'] = true;
            $_SESSION['adminStatus'] = true;
            header("Location: ../admin/admin_panel.php");
            exit();
        }
        session_start();
        $_SESSION['user_id'] = $user->getUserId();
        $_SESSION['loggedIn'] = true;
        header("Location: ../../index.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../../template/css/styles.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
