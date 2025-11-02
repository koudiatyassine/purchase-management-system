<?php 
include '../includes/header.php';


class InvoiceController {
    private $invoiceManagement;

    public function __construct($invoiceManagement) {
        $this->invoiceManagement = $invoiceManagement;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_invoice'])) {
                $this->invoiceManagement->addInvoice($_POST['user_id'], $_POST['amount'], $_POST['status']);
            } elseif (isset($_POST['delete_invoice'])) {
                $this->invoiceManagement->deleteInvoice($_POST['invoice_id']);
            } elseif (isset($_POST['delete_selected_invoices'])) {
                if (isset($_POST['invoice_ids'])) {
                    $this->invoiceManagement->deleteSelectedInvoices($_POST['invoice_ids']);
                }
            } elseif (isset($_POST['update_invoice'])) {
                $this->invoiceManagement->updateInvoice($_POST['invoice_id'], $_POST['user_id'], $_POST['amount'], $_POST['status']);
            }
        }
        return $this->invoiceManagement->getAllInvoices();
    }
}
include '../includes/footer.php';

?>