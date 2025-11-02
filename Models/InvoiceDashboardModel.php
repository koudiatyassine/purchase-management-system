<?php

require_once '../config/database.php'; // Include the database connection

class InvoiceDashboardModel {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection(); // Get the database connection
    }

    // Example method: Fetch the total number of invoices
    public function fetchInvoiceStats() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS invoice_count FROM invoices");
        $stmt->execute();
        return $stmt->fetch();
    }

    // Add more methods for invoice-specific operations as needed
}

?>
