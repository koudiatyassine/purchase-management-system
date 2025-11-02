<?php
class SessionManager {
    // Start the session if it hasn't already started
    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Check if the admin is logged in
    public function isAdminLoggedIn() {
        return isset($_SESSION['admin_id']);
    }

    // Redirect to the login page if the admin is not logged in
    public function redirectToLogin() {
        header('Location: ../pages/admin_login.php');
        exit();
    }

    // Handle logout
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ../pages/admin_login.php');
        exit();
    }
}
?>
