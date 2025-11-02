<?php

require_once '../models/CustomerDashboardModel.php'; // Include the model

class CustomerDashboardController {
    private $model;

    public function __construct() {
        $this->model = new CustomerDashboardModel();
        session_start(); // Start the session
    }

    public function checkLogin() {
        // Redirect if the admin is not logged in
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ../pages/admin_login.php');
            exit();
        }
    }

    public function getCustomerData() {
        // Fetch data from the model
        return $this->model->fetchCustomerData();
    }
}

?>
