<?php

require_once '../models/AdminDashboardModel.php'; // Include the model

class AdminDashboardController {
    private $model;

    public function __construct() {
        $this->model = new AdminDashboardModel();
        session_start(); // Start the session
    }

    public function checkLogin() {
        // Redirect if the admin is not logged in
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ../pages/admin_login.php');
            exit();
        }
    }

    public function getDashboardData() {
        // Fetch data from the model
        return $this->model->fetchDashboardData();
    }
}

?>
