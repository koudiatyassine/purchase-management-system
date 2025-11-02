<?php

class InvoiceManagement {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function userExists($user_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function addInvoice($user_id, $amount, $status) {
        if (!$this->userExists($user_id)) {
            $_SESSION['message'] = "L'utilisateur spécifié n'existe pas.";
            return false;
        }
        $stmt = $this->pdo->prepare("INSERT INTO invoices (user_id, amount, status) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $amount, $status]);
    }

    public function deleteInvoice($invoice_id) {
        $stmt = $this->pdo->prepare("DELETE FROM invoices WHERE id = ?");
        return $stmt->execute([$invoice_id]);
    }

    public function deleteSelectedInvoices($invoice_ids) {
        $placeholders = implode(',', array_fill(0, count($invoice_ids), '?'));
        $stmt = $this->pdo->prepare("DELETE FROM invoices WHERE id IN ($placeholders)");
        return $stmt->execute($invoice_ids);
    }

    public function updateInvoice($invoice_id, $user_id, $amount, $status) {
        if (!$this->userExists($user_id)) {
            $_SESSION['message'] = "L'utilisateur spécifié n'existe pas.";
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE invoices SET user_id = ?, amount = ?, status = ? WHERE id = ?");
        return $stmt->execute([$user_id, $amount, $status, $invoice_id]);
    }

    public function getInvoiceById($invoice_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM invoices WHERE id = ?");
        $stmt->execute([$invoice_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllInvoices() {
        $stmt = $this->pdo->query("SELECT * FROM invoices ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>