<?php

require_once '../controllers/AdminLoginProcessController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $controller = new AdminLoginProcessController();
    $controller->processLogin($email, $password);
}

?>
