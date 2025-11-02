<?php

require_once '../config/database.php';

class AdminLoginProcessModel {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function findAdminByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
