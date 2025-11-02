<?php
class UserManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM admins");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function addUser($email, $password, $role) {
        try {
            // Vérifier si l'email existe déjà
            $checkStmt = $this->pdo->prepare("SELECT COUNT(*) FROM admins WHERE email = ?");
            $checkStmt->execute([$email]);
            if ($checkStmt->fetchColumn() > 0) {
                return "Erreur : L'email existe déjà.";
            }
    
            // Hachage du mot de passe avec SHA-256
            $hashedPassword = hash('sha256', $password);
    
            // Insérer le nouvel utilisateur dans la base de données
            $stmt = $this->pdo->prepare("INSERT INTO admins (email, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$email, $hashedPassword, $role]);
    
            return "Utilisateur ajouté avec succès.";
        } catch (PDOException $e) {
            return "Erreur inattendue : " . $e->getMessage();
        }
    }
    

    public function editUser($id, $email, $role) {
        try {
            $stmt = $this->pdo->prepare("UPDATE admins SET email = ?, role = ? WHERE id = ?");
            $stmt->execute([$email, $role, $id]);
            return "Utilisateur modifié avec succès.";
        } catch (PDOException $e) {
            return "Erreur inattendue : " . $e->getMessage();
        }
    }

    public function deleteUser($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM admins WHERE id = ?");
            $stmt->execute([$id]);
            return "Utilisateur supprimé avec succès.";
        } catch (PDOException $e) {
            return "Erreur inattendue : " . $e->getMessage();
        }
    }
}
?>


