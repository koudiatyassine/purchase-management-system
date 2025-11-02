<?php

require_once '../models/CustomerDashboardModel.php';

class CustomerDashboardController {
    private $model;

    public function __construct() {
        $this->model = new CustomerDashboardModel();
    }

    public function getInvoicesByCustomerId($customerId) {
        return $this->model->getInvoicesByCustomerId($customerId);
    }
}
