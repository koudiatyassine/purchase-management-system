<?php
// Start the session to check if the user is logged in
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {  // Changed 'user_id' to 'customer_id'
    // If the user is not logged in, redirect to the login page
    header('Location: customer_login.php');
    exit;
}
include '../includes/header.php'; 
// Connect to the database via $db object
require_once '../config/database.php';
$pdo = $db->getConnection();

// Get the logged-in user's ID
$user_id = $_SESSION['customer_id']; // Ensure we're using the correct session variable

try {
    // Prepare the query to fetch invoices for the logged-in user
    $stmt = $pdo->prepare('SELECT * FROM invoices WHERE user_id = :user_id ORDER BY created_at DESC');
    $stmt->execute(['user_id' => $user_id]);
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debugging: Check if any invoices were fetched
    if (!$invoices) {
        echo '';
    }
} catch (Exception $e) {
    die('Error retrieving invoices: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Invoice Dashboard</title>
  

</head>
<body>
<div class="top-buttons-container" style=".top-buttons-container {position: absolute;top: 20px;right: 20px;display: flex;gap: 10px; /* Space between the back and logout buttons */align-items: center; /* Vertically align the buttons */}.btn-back, .btn-logout {background-color: #3498db;color: white;border: none;padding: 10px 20px;font-size: 16px;border-radius: 5px;cursor: pointer;transition: background-color 0.3s, transform 0.2s;font-family: 'Arial', sans-serif;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);width: 150px; }.btn-back:hover {background-color: #2980b9;transform: translateY(-2px); }.btn-back:focus {outline: none;box-shadow: 0px 0px 5px 2px #2980b9;}.btn-logout:hover {background-color: #c0392b;transform: translateY(-2px); }.btn-logout:focus { outline: none; box-shadow: 0px 0px 5px 2px #c0392b;}}">  
    <form action="logout_customer.php" method="POST" style="display: inline;">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</div>
<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>Customer Invoice Dashboard</h2>
    </div>

    <!-- If no invoices are found -->
    <?php if (empty($invoices)): ?>
        <p>No invoices found.</p>
    <?php else: ?>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($invoice['id']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['amount']); ?> USD</td>
                        <td><?php echo htmlspecialchars($invoice['status']); ?></td>
                        <td><a href="customer_invoice_details.php?invoice_id=<?php echo $invoice['id']; ?>" style="text-decoration: none; color: red;">View Details</a></td>                       
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
<?php include '../includes/footer.php'; ?>