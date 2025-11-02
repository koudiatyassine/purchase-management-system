<?php

require_once '../config/SessionManager.php';
require_once '../config/database.php';
require_once '../Models/UserManager.php';
require_once '../controllers/UserController.php';

$sessionManager = new SessionManager();
$sessionManager->startSession();

if (!$sessionManager->isAdminLoggedIn()) {
    $sessionManager->redirectToLogin();
    exit();
}

$database = new Database();
$pdo = $database->getConnection();

$userManager = new UserManager($pdo);
$userController = new UserController($userManager);

$message = $userController->handleRequest();
$users = $userController->listUsers();

?>
<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
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




    </style>
</head>
<body>
<header>
    <h1>Gestion des utilisateurs</h1>
</header>
<div class="top-buttons-container">
    <form action="../views/admin_dashboard.php" method="get" style="display: inline;">
        <button type="submit" class="btn btn-back">Back</button>
    </form>
    <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>
</div>
<?php if (!empty($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<div class="container">
    <h2>Ajouter un utilisateur</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <select name="role" required>
            <option value="super_admin">Super Admin</option>
            <option value="customer_manager">Customer Manager</option>
            <option value="product_manager">Product Manager</option>
            <option value="invoice_manager">Invoice Manager</option>
        </select>
        <button type="submit" name="add_user">Ajouter</button>
    </form>
</div>

<div class="container">
    <h2>Liste des utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <input type="email" name="email" value="<?= $user['email'] ?>" required>
                            <select name="role" required>
                                <option value="super_admin" <?= $user['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                                <option value="customer_manager" <?= $user['role'] === 'customer_manager' ? 'selected' : '' ?>>Customer Manager</option>
                                <option value="product_manager" <?= $user['role'] === 'product_manager' ? 'selected' : '' ?>>Product Manager</option>
                                <option value="invoice_manager" <?= $user['role'] === 'invoice_manager' ? 'selected' : '' ?>>Invoice Manager</option>
                            </select>
                            <button type="submit" name="edit_user">Modifier</button>
                            <button type="submit" name="delete_user">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php include '../includes/footer.php'; ?>


