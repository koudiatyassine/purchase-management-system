<?php
session_start();
require_once '../config/database.php';  // Database connection
require_once '../Controllers/ProductController.php';  // Controller

// Create controller instance and handle action
$productController = new ProductController($pdo);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
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
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    opacity: 0;
    z-index: 1000;
    transition: opacity 0.3s ease;
}

.modal.show {
    display: flex;
    opacity: 1;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    max-width: 600px;
    width: 100%;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.form-group {
    margin-bottom: 15px;
}

.btn {
    padding: 10px 15px;
    margin-right: 10px;
}



    </style>
</head>
<body>

<?php
// Display message if any
if (isset($_SESSION['message'])) {
    echo '<div class="alert">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}
?>
   <div class="top-buttons-container">
    <?php
    
    // Vérifier si la clé 'admin_role' est définie dans la session
    if (isset($_SESSION['admin_role'])) {
        $role = $_SESSION['admin_role']; // Récupérez le rôle de l'administrateur depuis la session
        
        // Définir la page de redirection en fonction du rôle
        if ($role == 'super_admin') {
            $redirectPage = "../views/admin_dashboard.php";
        } else {
            $redirectPage = "../views/product_dashboard.php";
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
    <h2>Product Management</h2>

    <!-- Product Add Form -->
    <h3>Add a New Product</h3>
    <form action="../Controllers/ProductController.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
    </form>

    <hr>

    <!-- Existing Products List -->
    <h3>Existing Products</h3>
    <?php
    $query = "SELECT * FROM products WHERE archived = 0";
    $stmt = $pdo->query($query);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($products) {
        foreach ($products as $product) {
            echo '<div class="product-card">';
            echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
            echo '<p>' . htmlspecialchars($product['description']) . '</p>';
            echo '<p>Price: $' . htmlspecialchars($product['price']) . '</p>';
            if ($product['image']) {
                echo '<img src="' . htmlspecialchars($product['image']) . '" alt="Product Image" style="width: 100px;">';
            }
            echo '<form action="../Controllers/ProductController.php" method="POST" style="display: inline;">
                    <input type="hidden" name="product_id" value="' . $product['id'] . '">
                    <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                  </form>';
            echo '<button class="btn btn-primary" onclick="editProduct(' . htmlspecialchars(json_encode($product)) . ')">Edit</button>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found.</p>';
    }
    ?>
</div>

<!-- Modal for Editing Product -->
<div id="editModal" class="modal" aria-hidden="true">
    <div class="modal-content" aria-labelledby="modalTitle">
        <h3 id="modalTitle">Edit Product</h3>
        <form action="../Controllers/ProductController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="editProductId">
            <div class="form-group">
                <label for="editName">Product Name:</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="editDescription">Description:</label>
                <textarea name="description" id="editDescription" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="editPrice">Price:</label>
                <input type="number" step="0.01" name="price" id="editPrice" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="editImage">Product Image:</label>
                <input type="file" name="image" id="editImage" class="form-control">
            </div>
            <button type="submit" name="update_product" class="btn btn-success">Update Product</button>
            <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>

<script>
function editProduct(product) {
    document.getElementById('editProductId').value = product.id;
    document.getElementById('editName').value = product.name;
    document.getElementById('editDescription').value = product.description;
    document.getElementById('editPrice').value = product.price;

    const modal = document.getElementById('editModal');
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
}

function closeModal() {
    const modal = document.getElementById('editModal');
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Fermer la modale en cliquant à l'extérieur
document.addEventListener('click', function (event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeModal();
    }
});

</script>

</body>
</html>
