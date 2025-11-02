<?php

require_once '../config/database.php';

class AdminLoginModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function authenticate($email, $password) {
        $sql = "SELECT * FROM admins WHERE email = :email AND password = :password";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR); // Make sure passwords are hashed and verified securely
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
