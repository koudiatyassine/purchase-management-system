<?php
// Démarrer la session au début du fichier, avant tout autre code
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclure les fichiers nécessaires après avoir démarré la session
require_once '../config/database.php';  // Connexion à la base de données
require_once '../Models/ProductManagement.php';  // Modèle

// Inclure header.php après avoir démarré la session (Vérifiez que ce fichier ne génère pas de sortie avant cette ligne)
include '../includes/header.php';

// Classe ProductController pour gérer la logique
class ProductController {
    private $productModel;

    public function __construct($pdo) {
        $this->productModel = new Product($pdo);
    }

    // Gérer les actions en fonction des soumissions de formulaires
    public function handleAction() {
        if (isset($_POST['add_product'])) {
            $this->addProduct();
        } elseif (isset($_POST['delete_product'])) {
            $this->deleteProduct();
        } elseif (isset($_POST['update_product'])) {
            $this->updateProduct();
        }
    }

    // Ajouter un nouveau produit
    private function addProduct() {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_FILES['image']['name'];

        if ($this->productModel->addProduct($name, $description, $price, $image)) {
            $_SESSION['message'] = "Produit ajouté avec succès.";
            $this->showMessageAndRedirect("Produit ajouté avec succès.");
        } else {
            $_SESSION['message'] = "Erreur lors de l'ajout du produit.";
            $this->showMessageAndRedirect("Erreur lors de l'ajout du produit.");
        }
    }

    // Supprimer un produit
    private function deleteProduct() {
        $product_id = $_POST['product_id'];
        if ($this->productModel->archiveProduct($product_id)) {
            $_SESSION['message'] = "Produit supprimé avec succès.";
            $this->showMessageAndRedirect("Produit supprimé avec succès.");
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression du produit.";
            $this->showMessageAndRedirect("Erreur lors de la suppression du produit.");
        }
    }

    // Mettre à jour un produit
    private function updateProduct() {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_FILES['image']['name'];

        if ($this->productModel->updateProduct($product_id, $name, $description, $price, $image)) {
            $_SESSION['message'] = "Produit mis à jour avec succès.";
            $this->showMessageAndRedirect("Produit mis à jour avec succès.");
        } else {
            $_SESSION['message'] = "Erreur lors de la mise à jour du produit.";
            $this->showMessageAndRedirect("Erreur lors de la mise à jour du produit.");
        }
    }

    // Afficher un message et un lien pour rediriger l'utilisateur
    private function showMessageAndRedirect($message) {
        echo "<p>$message</p>";
        echo '<a href="http://localhost/KoudiatYassine/Views/product_managment_view.php">Retourner à la page de gestion des produits</a>';
        exit();
    }
}

// Créer une instance du contrôleur et gérer l'action
$productController = new ProductController($pdo);
$productController->handleAction();

// Inclure le footer après avoir exécuté la logique (pas de sortie avant cette ligne)
include '../includes/footer.php';
?>
