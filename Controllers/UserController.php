<?php
class UserController {
    private $userManager;

    public function __construct($userManager) {
        $this->userManager = $userManager;
    }

    public function handleRequest() {
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_user'])) {
                $message = $this->userManager->addUser($_POST['email'], $_POST['password'], $_POST['role']);
            } elseif (isset($_POST['edit_user'])) {
                $message = $this->userManager->editUser($_POST['id'], $_POST['email'], $_POST['role']);
            } elseif (isset($_POST['delete_user'])) {
                $message = $this->userManager->deleteUser($_POST['id']);
            } elseif (isset($_POST['logout'])) {
                $sessionManager = new SessionManager();
                $sessionManager->logout();
            }
        }

        return $message;
    }

    public function listUsers() {
        return $this->userManager->getAllUsers();
    }
}
?>