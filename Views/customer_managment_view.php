<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/CustomerManagement.php';
require_once __DIR__ . '/../Controllers/CustomerController.php';

// Initialiser la connexion à la base de données
$database = new Database();
$pdo = $database->getConnection();

// Créer une instance de CustomerManagement avec la connexion PDO
$customerManagement = new CustomerManagement($pdo);

// Créer une instance de CustomerController
$controller = new CustomerController($customerManagement);

// Récupérer les utilisateurs
$users = $controller->handleRequest();
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
    z-index: 1000;
    transition: opacity 0.3s ease; /* Ajout d'une transition */
    opacity: 0; /* Caché par défaut */
}

#editModal.show {
    display: flex;
    opacity: 1; /* Visible lorsqu'on ajoute la classe 'show' */
}

#editModal form {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

#editModal input, 
#editModal select, 
#editModal button {
    width: 100%;
    margin-bottom: 10px;
}


    </style>
</head>
<body>
<header>
    <h1>Customer Management System</h1>
</header>

<div class="container">
    <h2>Gestion des utilisateurs</h2>
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
            $redirectPage = "../views/customer_dashboard.php";
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
    <form action="" method="POST">
        <h3>Ajouter un nouvel utilisateur</h3>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Téléphone">
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="add_user">Ajouter</button>
    </form>

    <hr>

    <form action="" method="POST" id="deleteForm">
        <table>
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><input type="checkbox" name="user_ids[]" value="<?= $user['id']; ?>"></td>
                    <td><?= $user['id']; ?></td>
                    <td><?= $user['username']; ?></td>
                    <td><?= $user['email']; ?></td>
                    <td><?= $user['phone']; ?></td>
                    <td>
                        <button type="button" onclick="editUser(<?= $user['id']; ?>, '<?= $user['username']; ?>', '<?= $user['email']; ?>', '<?= $user['phone']; ?>')">Modifier</button>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" name="delete_user">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" name="delete_selected">Supprimer sélectionnés</button>
    </form>

    <div id="editModal" aria-hidden="true">
    <form action="" method="POST" aria-labelledby="editModalTitle">
        <h3 id="editModalTitle">Modifier un utilisateur</h3>
        <input type="hidden" name="edit_user_id" id="edit_user_id">
        <input type="text" name="username" id="edit_username" placeholder="Nom d'utilisateur" required>
        <input type="email" name="email" id="edit_email" placeholder="Email" required>
        <input type="text" name="phone" id="edit_phone" placeholder="Téléphone">
        <button type="submit" name="edit_user">Modifier</button>
        <button type="button" onclick="closeModal()">Fermer</button>
    </form>
</div>

</div>

<script>
   function editUser(id, username, email, phone) {
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_phone').value = phone;

    const modal = document.getElementById('editModal');
    modal.style.display = 'flex';
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
}

function closeModal() {
    const modal = document.getElementById('editModal');
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300); // Correspond à la durée de la transition
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
<?php include '../includes/footer.php'; ?>
