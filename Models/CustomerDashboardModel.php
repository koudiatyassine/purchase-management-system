<?php

require_once '../config/database.php'; // Include the database connection

class CustomerDashboardModel {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection(); // Get the database connection
    }

    // Add methods for database interactions specific to customers
    public function fetchCustomerData() {
        // Example: Fetch customer-related statistics or data
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS customer_count FROM users");
        $stmt->execute();
        return $stmt->fetch();
    }
}

?>
