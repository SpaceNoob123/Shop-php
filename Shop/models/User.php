<?php

class User
{
    private $userId;
    private $username;
    private $email;
    private $password;
    private $isAdmin;

    // Конструктор
    public function __construct($userId, $username, $email, $password, $isAdmin) {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
    }
    public function login($email, $password, $conn) {
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return true; // Успешный вход
            }
        }

        return false; // Неудачный вход
    }

    public function register($username, $email, $password, $conn) {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $username, $email, $password);
            $result = $stmt->execute();

            return $result;
        }

        return false;
    }
    public function  getIsAdmin(){
        return $this->isAdmin;
    }
    public function getUserId() {
        return $this->userId;
    }
    public function getUsername() {
        return $this->username;
    }
    public static function getAllUsers($conn) {
        $users = array();

        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $userId = isset($row['id']) ? $row['id'] : null;
                $username = isset($row['name']) ? $row['name'] : null;
                $email = isset($row['email']) ? $row['email'] : null;
                $password = isset($row['password']) ? $row['password'] : null;
                $isAdmin = isset($row['isAdmin']) ? $row['isAdmin'] : null;

                $user = new User($userId, $username, $email, $password, $isAdmin);
                $users[] = $user;
            }
            $result->free();
        }

        return $users;
    }
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function isAdmin() {
        return $this->isAdmin;
    }
}