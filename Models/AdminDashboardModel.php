<?php

require_once '../config/Database.php'; // Ensure the database connection is included

class AdminDashboardModel {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection(); // Get the database connection
    }

    // Add methods for database interactions, e.g., fetching admin-related stats
    public function fetchDashboardData() {
        // Example: Fetch some statistics or data for the dashboard
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS user_count FROM users");
        $stmt->execute();
        return $stmt->fetch();
    }
}

?>
