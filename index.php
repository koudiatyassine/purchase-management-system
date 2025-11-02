<?php
class Redirector {
    // Method to redirect to a given URL
    public static function redirectTo($url) {
        header("Location: $url");
        exit();
    }
}

// Redirect to the landing page
Redirector::redirectTo('Views/landing.php');
?>
