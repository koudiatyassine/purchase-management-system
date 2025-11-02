<?php
class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter un nouveau produit
    public function addProduct($name, $description, $price, $image) {
        // Assainir les entrées pour prévenir les injections SQL
        $name = htmlspecialchars($name);
        $description = htmlspecialchars($description);
        $price = filter_var($price, FILTER_VALIDATE_FLOAT);

        // Vérifier les extensions de fichier autorisées et la taille du fichier
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = pathinfo($image, PATHINFO_EXTENSION);
        $targetDir = "../assets/images/";

        // Vérifier si le répertoire existe, sinon le créer
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $target = $targetDir . basename($image);

        // Valider l'extension du fichier et la taille
        if (!in_array(strtolower($fileExtension), $allowedExtensions) || $_FILES['image']['size'] > 2 * 1024 * 1024) {
            return false;
        }

        // Insérer le produit dans la base de données
        $query = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute([$name, $description, $price, $image])) {
            // Déplacer le fichier téléchargé vers le répertoire cible
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            return true;
        } else {
            return false;
        }
    }

    // Archiver un produit (suppression douce)
    public function archiveProduct($product_id) {
        $query = "UPDATE products SET archived = 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$product_id]);
    }

    // Mettre à jour un produit
    public function updateProduct($product_id, $name, $description, $price, $image = null) {
        // Assainir les entrées pour prévenir les injections SQL
        $name = htmlspecialchars($name);
        $description = htmlspecialchars($description);
        $price = filter_var($price, FILTER_VALIDATE_FLOAT);

        // Préparer la requête
        $targetDir = "../assets/images/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (!empty($image)) {
            // Vérifier et déplacer le fichier téléchargé
            $target = $targetDir . basename($image);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = pathinfo($image, PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedExtensions) || $_FILES['image']['size'] > 2 * 1024 * 1024) {
                return false;
            }

            $query = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
            $params = [$name, $description, $price, $image, $product_id];
        } else {
            $query = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";
            $params = [$name, $description, $price, $product_id];
        }

        // Exécuter la requête
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute($params)) {
            // Déplacer le fichier téléchargé si une image est présente
            if (!empty($image)) {
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
            }
            return true;
        } else {
            return false;
        }
    }
}
?>
