
<?php
// Démarrer la session avant tout autre output
session_start(); 
 
// Configuration de la base de données
require_once '../config/database.php';

// Connexion à la base de données via l'objet $db
$pdo = $db->getConnection(); // Utilisation de la connexion existante dans `database.php`

// Inclure l'en-tête (header.php) après avoir démarré la session


// Définition du modèle
class CustomerLoginModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère l'utilisateur par son email
    public function getUserByEmail($email) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Error fetching user: ' . $e->getMessage());
        }
    }
}

// Définition du contrôleur
class CustomerLoginController {
    private $model;

    public function __construct($pdo) {
        $this->model = new CustomerLoginModel($pdo); // Initialisation du modèle
    }

    // Affiche le formulaire de connexion
    public function showLoginForm() {
        include 'customer_login_view.php'; // Inclure la vue
    }

    // Gère la soumission du formulaire de connexion
    public function handleLogin($email, $password) {
        try {
            // Vérification des identifiants de l'utilisateur
            $user = $this->model->getUserByEmail($email);
            if ($user && hash('sha256', $password) === $user['password']) {
                // Sauvegarder l'utilisateur connecté dans la session
                $_SESSION['customer_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                // Redirection vers le tableau de bord
                header('Location: ../views/customer_invoice_dashboard.php');
                exit;
            } else {
                return 'Invalid email or password.';
            }
        } catch (Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }
}

// Traitement du formulaire de connexion
$controller = new CustomerLoginController($pdo);
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $error_message = $controller->handleLogin($email, $password);
    if (!$error_message) {
        // Si la connexion est réussie, la redirection se fera automatiquement dans le contrôleur
        exit;
    }
}

// Affichage du formulaire de connexion
?>
<?php include '../includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
</head>
<body>

<div class="top-buttons-container" style=".top-buttons-container {position: absolute;top: 20px;right: 20px;display: flex;gap: 10px;align-items: center;}.btn-back, .btn-logout {background-color: #3498db;color: white;border: none;padding: 10px 20px;font-size: 16px;border-radius: 5px;cursor: pointer;transition: background-color 0.3s, transform 0.2s;font-family: 'Arial', sans-serif;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);width: 150px; }.btn-back:hover {background-color: #2980b9;transform: translateY(-2px); }.btn-back:focus {outline: none;box-shadow: 0px 0px 5px 2px #2980b9;}.btn-logout:hover {background-color: #c0392b;transform: translateY(-2px); }.btn-logout:focus { outline: none; box-shadow: 0px 0px 5px 2px #c0392b;}}">
    <form action="landing.php" method="get" style="display: inline;">
        <button type="submit" class="btn btn-back">Back</button>
    </form>
</div>

<div class="login-container">
    <div class="login-header">
        <h2>Customer Login</h2>
    </div>
    
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    
    <form action="customer_login.php" method="POST" class="login-form">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
        
        <button type="submit" class="login-button">Login</button>
    </form>
</div>

</body>
</html>

<?php 

include '../includes/footer.php'; 
?>
