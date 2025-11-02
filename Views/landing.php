<?php

class PurchaseManagementSystem {

    private $pdo;

    // Database credentials
    private $host = 'localhost';
    private $dbname = 'shopmasteryassine';
    private $username = 'root';
    private $password = '';

    public function __construct() {
        // Initialize the PDO connection
        $this->checkAndCreateDatabase();
    }

    // Method to check and create the database if it doesn't exist
    private function checkAndCreateDatabase() {
        try {
            $pdo = new PDO("mysql:host=$this->host", $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the database exists
            $stmt = $pdo->query("SHOW DATABASES LIKE '$this->dbname'");
            if ($stmt->rowCount() == 0) {
                // Database doesn't exist, create it
                $pdo->exec("CREATE DATABASE $this->dbname");
             

                // Use the new database
                $pdo->exec("USE $this->dbname");

                // Execute the SQL file to set up the database
                $this->executeSQLFile($pdo);
            } else {
                // Database exists, just use it
                $pdo->exec("USE $this->dbname");
            }

            // Now set up the connection for operations within the database
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the 'role' column exists in the 'admins' table
            $this->checkAndAddColumn();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Check if the 'role' column exists in the 'admins' table, and add it if necessary
    private function checkAndAddColumn() {
        $checkColumn = $this->pdo->prepare("SHOW COLUMNS FROM admins LIKE 'role'");
        $checkColumn->execute();
        $roleExists = $checkColumn->fetch();

        // Add the 'role' column if it doesn't exist
        if (!$roleExists) {
            $alterTable = "ALTER TABLE admins ADD role VARCHAR(50) NOT NULL DEFAULT 'customer_manager'";
            $this->pdo->exec($alterTable);
            echo "Column 'role' added to 'admins' table.<br>";
        }
    }

    // Method to execute the SQL file to create tables and other structures
    private function executeSQLFile($pdo) {
        $sqlFilePath = '../config/database.sql'; // Adjust the path if needed
        if (file_exists($sqlFilePath)) {
            $sql = file_get_contents($sqlFilePath);
            if (!empty($sql)) {
                $pdo->exec($sql);
            }
        } else {
            echo "Error: '$sqlFilePath' file not found.";
        }
    }

    // Method to check and create the database for customers
    public function checkAndCreateCustomerDatabase() {
        try {
            $pdo = new PDO("mysql:host=$this->host", $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the database exists
            $stmt = $pdo->query("SHOW DATABASES LIKE '$this->dbname'");
            if ($stmt->rowCount() == 0) {
                // Database doesn't exist, create it
                $pdo->exec("CREATE DATABASE $this->dbname");
                echo "Database '$this->dbname' created successfully.<br>";

                // Use the new database
                $pdo->exec("USE $this->dbname");

                // Execute the SQL file to set up the database
                $this->executeSQLFile($pdo);
            } else {
                // Database exists, just use it
                $pdo->exec("USE $this->dbname");
            }

            // Now set up the connection for operations within the database
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Instantiate the class and handle database creation
$purchaseSystem = new PurchaseManagementSystem();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - ShopMaster System</title>
    <link rel="icon" href="../assets/images/favicon.png" type="image/png">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <h2>Welcome to ShopMaster!</h2>
        <p>Your one-stop platform to effortlessly manage and streamline all your purchase needs. Whether you're a customer or an administrator, our system is designed with precision and simplicity to make every interaction seamless and intuitive.</p>
        
        <p><strong>For Customers:</strong> View your invoices with ease and stay updated on your purchases. Enjoy a personalized experience with organized invoices and secure options for managing your purchase history.</p>
        
        <p><strong>For Admins:</strong> Take full control with an advanced dashboard to oversee user activities, manage customer accounts, track products, and monitor invoices. Admin roles are categorized into:</p>
        <p><strong>Full Administrator:</strong> Complete access to all system features.</p>
        <p><strong>Administrator for Customer Management:</strong> Manage customer accounts and details.</p>
        <p><strong>Administrator for Product Management:</strong> Oversee product listings and inventory.</p>
        <p><strong>Administrator for Invoice Management:</strong> Track and manage invoices efficiently.</p>
        
        <p>Our mission is to simplify purchase management, making it smarter, faster, and more efficient than ever.</p>
        
        <p><strong>Your journey to effortless purchasing begins here. Choose your path:</strong></p>

        <div class="button-container">
            <button id="customerButton">Yes, I am a Customer</button>
            <button id="adminButton">Yes, I am an Admin</button>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#adminButton').click(function() {
                // Send an AJAX request to check the database and create it if needed
                $.ajax({
                    url: '?action=checkAndCreate', // Send the action via GET
                    type: 'GET',
                    success: function(response) {
                        // If the database check is successful, redirect to the admin login page
                        window.location.href = 'admin_login.php';
                    },
                    error: function(xhr, status, error) {
                        alert('There was an error with the database setup. Please try again.');
                    }
                });
            });

            $('#customerButton').click(function() {
                // Send an AJAX request to check the customer database and create it if needed
                $.ajax({
                    url: '?action=checkAndCreateCustomer', // Send the action via GET
                    type: 'GET',
                    success: function(response) {
                        // If the database check is successful, redirect to the customer login page
                        window.location.href = 'customer_login.php';
                    },
                    error: function(xhr, status, error) {
                        alert('There was an error with the database setup. Please try again.');
                    }
                });
            });
        });
    </script>

    <?php include '../includes/footer.php'; ?>
</body>
</html>