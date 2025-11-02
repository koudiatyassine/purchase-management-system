<?php

require_once '../config/database.php'; // Include database connection

class ProductDashboardModel {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection(); // Establish database connection
    }

    // Example: Fetch total number of products
    public function fetchProductStats() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS product_count FROM products");
        $stmt->execute();
        return $stmt->fetch();
    }

    // Add more methods as needed, e.g., to fetch categories or product details
}

?>
