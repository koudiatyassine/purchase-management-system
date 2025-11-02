<?php


class CustomerManagement {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function checkLogin() {
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ../login.php');
            exit();
        }
    }

    public function addUser($username, $email, $phone, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function deleteUser($userId) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function updateUser($userId, $username, $email, $phone) {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$username, $email, $phone, $userId]);
    }

    public function getUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>