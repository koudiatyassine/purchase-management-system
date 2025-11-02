<?php

require_once '../controllers/AdminLoginController.php';

$controller = new AdminLoginController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->showLoginForm();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $controller->handleLogin($email, $password);
}

?>
