<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

include '../config/database.php'; // Database connection

if (!$pdo) {
    die("Database connection error");
}

// Invoice Class
class Invoice
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get an invoice by its ID
    public function getInvoiceById($invoiceId)
    {
        $query = "SELECT * FROM invoices WHERE id = :invoice_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':invoice_id', $invoiceId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Create an instance of the Invoice class
$invoiceClass = new Invoice($pdo);

// Retrieve the invoice ID from the URL parameters
if (isset($_GET['invoice_id'])) {
    $invoiceId = $_GET['invoice_id'];
    
    // Get the invoice details from the database
    $invoice = $invoiceClass->getInvoiceById($invoiceId);

    if (!$invoice) {
        die("Invoice not found.");
    }
} else {
    die("No invoice ID specified.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .invoice-header {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details p {
            margin: 5px 0;
        }
        .logout-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="invoice-container">
        <div class="invoice-header">
            <h2>Invoice #<?php echo htmlspecialchars($invoice['id']); ?></h2>
            <p>Creation Date: <?php echo htmlspecialchars($invoice['created_at']); ?></p>
        </div>

        <div class="invoice-details">
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($invoice['user_id']); ?></p>
            <p><strong>Amount:</strong> €<?php echo htmlspecialchars($invoice['amount']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($invoice['status']); ?></p>
        </div>
    </div>

    <div class="logout-container">
        <form action="../views/invoice_management_view.php" method="get">
            <button type="submit" class="btn btn-logout">Back</button>
        </form>
    </div>
</body>
</html>
