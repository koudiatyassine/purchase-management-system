<?php

require_once '../models/InvoiceDashboardModel.php'; // Include the model
require_once '../config/SessionManager.php'; // Include the session manager

class InvoiceDashboardController {
    private $model;
    private $sessionManager;

    public function __construct() {
        $this->model = new InvoiceDashboardModel();
        $this->sessionManager = new SessionManager();
    }

    public function checkLogin() {
        // Start session and check if admin is logged in
        $this->sessionManager->startSession();

        if (!$this->sessionManager->isAdminLoggedIn()) {
            $this->sessionManager->redirectToLogin();
        }
    }

    public function getInvoiceStats() {
        // Fetch data from the model
        return $this->model->fetchInvoiceStats();
    }
}

?>
