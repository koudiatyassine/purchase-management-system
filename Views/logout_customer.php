<?php
class SessionManager {
    // Start the session
    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Check if the admin is logged in
    public static function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    // Logout the admin and destroy the session
    public static function logout() {
        // Unset all session variables
        session_unset();
        
        // Destroy the session
        session_destroy();
        
        // Redirect to the login page
        header("Location: customer_login.php");
        exit();
    }
}

// Start the session
SessionManager::startSession();

// Check if the admin is logged in and then log out
if (SessionManager::isAdminLoggedIn()) {
    SessionManager::logout();
} else {
    // Redirect to the login page if not logged in
    header("Location: customer_login.php");
    exit();
}
?>
