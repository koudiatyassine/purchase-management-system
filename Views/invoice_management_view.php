<?php

// View: invoice_management_view.php
require_once '../config/database.php';
require_once '../Models/InvoiceManagement.php';
require_once '../Controllers/InvoiceController.php';

$database = new Database();
$pdo = $database->getConnection();

$invoiceManagement = new InvoiceManagement($pdo);
$controller = new InvoiceController($invoiceManagement);

$invoices = $controller->handleRequest();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management</title>

    <style>
        #editModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #editModal form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        #editModal input, #editModal select, #editModal button {
            width: 100%;
            margin-bottom: 10px;
        }
        /* Container for top buttons (Back and Logout) */
.top-buttons-container {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    gap: 10px; /* Space between the back and logout buttons */
    align-items: center; /* Vertically align the buttons */
}

/* Style for the back button */
.btn-back, .btn-logout {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    font-family: "Arial", sans-serif;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    width: 150px; /* Ensure both buttons have the same width */
}

/* Style for hover effect on the back button */
.btn-back:hover {
    background-color: #2980b9;
    transform: translateY(-2px); /* Hover effect */
}

/* Style for focus on the back button */
.btn-back:focus {
    outline: none;
    box-shadow: 0px 0px 5px 2px #2980b9;
}

/* Style for the logout button hover effect */
.btn-logout:hover {
    background-color: #c0392b;
    transform: translateY(-2px); /* Hover effect */
}

/* Style for focus on the logout button */
.btn-logout:focus {
    outline: none;
    box-shadow: 0px 0px 5px 2px #c0392b;
}


    </style>
</head>
<body>
<header>
    <h1>Invoice Management System</h1>
</header>
<div class="top-buttons-container">
    <?php
    session_start(); // Si vous n'avez pas encore démarré la session
    
    // Vérifier si la clé 'admin_role' est définie dans la session
    if (isset($_SESSION['admin_role'])) {
        $role = $_SESSION['admin_role']; // Récupérez le rôle de l'administrateur depuis la session
        
        // Définir la page de redirection en fonction du rôle
        if ($role == 'super_admin') {
            $redirectPage = "../views/admin_dashboard.php";
        } else {
            $redirectPage = "../views/invoice_dashboard.php";
        }
    } 
    ?>
    <!-- Formulaire pour revenir à la page du tableau de bord correspondant au rôle -->
    <form action="<?= $redirectPage ?>" method="get" style="display: inline;">
        <button type="submit" class="btn btn-back">Back</button>
    </form>

    <!-- Formulaire pour se déconnecter -->
    <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</div>

<div class="container">
    <h2>Invoice Management</h2>

    <form action="" method="POST">
        <h3>Add a New Invoice</h3>
        <input type="text" name="user_id" placeholder="User ID" required>
        <input type="number" name="amount" placeholder="Amount" required>
        <select name="status">
            <option value="paid">Paid</option>
            <option value="pending">Pending</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <button type="submit" name="add_invoice">Add</button>
    </form>

    <hr>

    <form action="" method="POST">
        <table>
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>User ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><input type="checkbox" name="invoice_ids[]" value="<?= $invoice['id']; ?>"></td>
                    <td><?= $invoice['id']; ?></td>
                    <td><?= $invoice['user_id']; ?></td>
                    <td><?= $invoice['amount']; ?></td>
                    <td><?= $invoice['status']; ?></td>
                    <td><?= $invoice['created_at']; ?></td>
                    <td>
                        <button type="button" onclick="editInvoice(<?= $invoice['id']; ?>, '<?= $invoice['user_id']; ?>', '<?= $invoice['amount']; ?>', '<?= $invoice['status']; ?>')">Edit</button>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="invoice_id" value="<?= $invoice['id']; ?>">
                            <button type="submit" name="delete_invoice">Delete</button>
                        </form>
                        <div>
    <form action="print_invoice.php" method="get">
        <input type="hidden" name="invoice_id" value="<?php echo htmlspecialchars($invoice['id']); ?>">
        <button type="submit">Print</button>
    </form>
</div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" name="delete_selected_invoices">Delete Selected</button>
    </form>

    <div id="editModal">
        <form action="" method="POST">
            <h3>Edit an Invoice</h3>
            <input type="hidden" name="invoice_id" id="edit_invoice_id">
            <input type="text" name="user_id" id="edit_user_id" placeholder="User ID" required>
            <input type="number" name="amount" id="edit_amount" placeholder="Amount" required>
            <select name="status" id="edit_status">
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <button type="submit" name="update_invoice">Update</button>
            <button type="button" onclick="closeModal()">Close</button>
        </form>
    </div>
</div>

<script>
    function editInvoice(id, user_id, amount, status) {
        document.getElementById('edit_invoice_id').value = id;
        document.getElementById('edit_user_id').value = user_id;
        document.getElementById('edit_amount').value = amount;
        document.getElementById('edit_status').value = status;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
</body>
</html>
