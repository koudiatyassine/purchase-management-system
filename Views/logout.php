<?php
// Inclure le fichier du contrôleur
require_once '../controllers/AdminLoginProcessController.php';

// Créer une instance du contrôleur
$controller = new AdminLoginProcessController();

// Appeler la méthode de déconnexion
$controller->logout();
?>
