<?php

class DatabaseInitializer {
    private $host = 'localhost';
    private $dbname = 'shopmasteryassine';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct() {
        try {
            // Connecter au serveur MySQL sans spécifier de base de données
            $this->pdo = new PDO("mysql:host={$this->host}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }
    }

    public function initializeDatabase() {
        try {
            // Vérifier si la base de données existe
            $stmt = $this->pdo->query("SHOW DATABASES LIKE '{$this->dbname}'");
            if ($stmt->rowCount() == 0) {
                // Si la base de données n'existe pas, la créer
                $this->pdo->exec("CREATE DATABASE {$this->dbname}");
                echo "Database '{$this->dbname}' created.<br>";

                // Sélectionner la base de données nouvellement créée
                $this->pdo->exec("USE {$this->dbname}");

                // Importer le fichier SQL pour initialiser la structure de la base
                $sqlFilePath = '../config/database.sql';
                if (file_exists($sqlFilePath)) {
                    $sql = file_get_contents($sqlFilePath);
                    if (!empty($sql)) {
                        $this->pdo->exec($sql);
                        echo "Database structure initialized from SQL file.<br>";
                    }
                } else {
                    echo "Error: SQL file '{$sqlFilePath}' not found.<br>";
                }
            } else {
                // Si la base de données existe déjà, utiliser celle-ci
                $this->pdo->exec("USE {$this->dbname}");
                echo "Database '{$this->dbname}' already exists and is in use.<br>";
            }
        } catch (PDOException $e) {
            echo "Database Initialization Error: " . $e->getMessage();
        }
    }
}

// Instancier et initialiser la base de données
$initializer = new DatabaseInitializer();
$initializer->initializeDatabase();
?>
