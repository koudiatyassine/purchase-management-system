<?php 
// Start the session at the very beginning
session_start();

// Configuration de la base de données
require_once '../config/database.php';

// Connexion à la base de données via l'objet $db
$pdo = $db->getConnection(); // Utilisation de la connexion existante dans `database.php`
include '../includes/header.php';

// Vérification de la session de l'utilisateur
if (!isset($_SESSION['customer_id'])) {
    header('Location: customer_login.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];
$invoice_id = $_GET['invoice_id'] ?? null;

// Récupérer les détails de l'utilisateur
$user_stmt = $pdo->prepare('SELECT id, username, email, phone FROM users WHERE id = :user_id');
$user_stmt->execute(['user_id' => $customer_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User details not found.";
    exit;
}

// Récupérer les détails de la facture
if ($invoice_id) {
    $invoice_stmt = $pdo->prepare('SELECT * FROM invoices WHERE id = :invoice_id AND user_id = :user_id');
    $invoice_stmt->execute([
        'invoice_id' => $invoice_id,
        'user_id' => $customer_id
    ]);
    $invoice = $invoice_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo "Invoice not found or you do not have permission to view it.";
        exit;
    }
} else {
    echo "Invalid invoice ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Details</title>
</head>
<body>
<div class="top-buttons-container" style=".top-buttons-container {position: absolute;top: 20px;right: 20px;display: flex;gap: 10px; /* Space between the back and logout buttons */align-items: center; /* Vertically align the buttons */}.btn-back, .btn-logout {background-color: #3498db;color: white;border: none;padding: 10px 20px;font-size: 16px;border-radius: 5px;cursor: pointer;transition: background-color 0.3s, transform 0.2s;font-family: 'Arial', sans-serif;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);width: 150px; }.btn-back:hover {background-color: #2980b9;transform: translateY(-2px); }.btn-back:focus {outline: none;box-shadow: 0px 0px 5px 2px #2980b9;}.btn-logout:hover {background-color: #c0392b;transform: translateY(-2px); }.btn-logout:focus { outline: none; box-shadow: 0px 0px 5px 2px #c0392b;}}">
    <form action="../views/customer_invoice_dashboard.php" method="get" style="display: inline;">
        <button type="submit" class="btn btn-back">Back</button>
    </form>    
    <form action="logout_customer.php" method="POST" style="display: inline;">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</div>
<div class="container">
    <h2>Invoice Details</h2>
    
    <!-- User Details -->
    <h3>User Details</h3>
    <table>
        <tr>
            <th>User ID</th>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
        </tr>
    </table>
    
    <!-- Invoice Details -->
    <h3>Invoice Information</h3>
    <table>
        <tr>
            <th>Invoice ID</th>
            <td><?php echo htmlspecialchars($invoice['id']); ?></td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td>$<?php echo number_format($invoice['total'], 2); ?></td>
        </tr>
        <tr>
            <th>Amount Paid</th>
            <td>$<?php echo number_format($invoice['amount'], 2); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo htmlspecialchars($invoice['status']); ?></td>
        </tr>
        <tr>
            <th>Created At</th>
            <td><?php echo htmlspecialchars($invoice['created_at']); ?></td>
        </tr>
    </table>
    </div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
