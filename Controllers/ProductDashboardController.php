<?php

require_once '../models/ProductDashboardModel.php'; // Include the model
require_once '../config/SessionManager.php'; // Include the session manager

class ProductDashboardController {
    private $model;
    private $sessionManager;

    public function __construct() {
        $this->model = new ProductDashboardModel();
        $this->sessionManager = new SessionManager();
    }

    public function checkLogin() {
        $this->sessionManager->startSession();
        if (!$this->sessionManager->isAdminLoggedIn()) {
            $this->sessionManager->redirectToLogin(); // Redirect to login if not logged in
        }
    }

    public function getProductStats() {
        return $this->model->fetchProductStats(); // Fetch product stats from the model
    }
}

?>
