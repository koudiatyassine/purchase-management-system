<?php
if (!class_exists('Database')) {
    class Database {
        private $host = 'localhost';
        private $dbname = 'shopmasteryassine';
        private $username = 'root';
        private $password = '';
        private $pdo;

        public function __construct() {
            try {
                // Nous utilisons la méthode pour vérifier et créer la base avant la connexion
                $this->initializeDatabase(); // Appel de la méthode pour vérifier et créer la DB avant d'essayer de se connecter

                // Maintenant que la base de données existe, nous pouvons établir la connexion
                $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4", $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }

        public function getConnection() {
            return $this->pdo;
        }

        // Cette méthode sera appelée pour vérifier et créer la base de données
        private function initializeDatabase() {
            try {
                // Connexion sans spécifier de base de données
                $pdo = new PDO("mysql:host={$this->host}", $this->username, $this->password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Vérification de l'existence de la base de données
                $stmt = $pdo->query("SHOW DATABASES LIKE '{$this->dbname}'");
                if ($stmt->rowCount() == 0) {
                    // Création de la base de données
                    $pdo->exec("CREATE DATABASE {$this->dbname}");
                }

                // Connexion à la base de données nouvellement créée ou existante
                $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4", $this->username, $this->password);
            } catch (PDOException $e) {
                die("Erreur de création ou de connexion à la base de données: " . $e->getMessage());
            }
        }
    }
}

// Initialiser et retourner la connexion PDO
$db = new Database();
$pdo = $db->getConnection();
?>
