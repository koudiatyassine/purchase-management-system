<?php include '../includes/header.php'; ?>
<?php

require_once '../models/AdminLoginModel.php';

class AdminLoginController {
    private $model;

    public function __construct() {
        $this->model = new AdminLoginModel();
    }

    
    public function showLoginForm() {
        require_once '../views/admin_login_view.php';
    }

    public function handleLogin($email, $password) {
        $admin = $this->model->authenticate($email, $password);

        if ($admin) {
            session_start();
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: /admin/dashboard');
        } else {
            header('Location: /admin/login?error=invalid_credentials');
        }
    }
}

?>
<?php include '../includes/footer.php'; ?>
