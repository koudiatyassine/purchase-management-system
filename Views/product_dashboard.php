<?php
require_once '../controllers/ProductDashboardController.php'; // Include the controller

$controller = new ProductDashboardController();
$controller->checkLogin(); // Ensure the admin is logged in

$data = $controller->getProductStats(); // Fetch product data
?>

<?php include '../includes/header.php'; ?>

<div class="admin-container">
<div class="top-buttons-container" style=".top-buttons-container {position: absolute;top: 20px;right: 20px;display: flex;gap: 10px; /* Space between the back and logout buttons */align-items: center; /* Vertically align the buttons */}.btn-back, .btn-logout {background-color: #3498db;color: white;border: none;padding: 10px 20px;font-size: 16px;border-radius: 5px;cursor: pointer;transition: background-color 0.3s, transform 0.2s;font-family: 'Arial', sans-serif;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);width: 150px; }.btn-back:hover {background-color: #2980b9;transform: translateY(-2px); }.btn-back:focus {outline: none;box-shadow: 0px 0px 5px 2px #2980b9;}.btn-logout:hover {background-color: #c0392b;transform: translateY(-2px); }.btn-logout:focus { outline: none; box-shadow: 0px 0px 5px 2px #c0392b;}}">
    <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</div>

    <div class="dashboard-header">
        <h2>Product Dashboard</h2>
    </div>

    <div class="dashboard-content">
        <ul class="admin-menu">
            <li><a href="product_managment_view.php" class="menu-item">Manage Product</a></li>
        </ul>
        
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    function confirmLogout() {
        return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');
    }
</script>
