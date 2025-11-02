-- Création de la base de données
CREATE DATABASE IF NOT EXISTS shopmasteryassine;

-- Sélectionner la base de données
USE shopmasteryassine;

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS admins (
    id INT(11) NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'customer_manager', 'product_manager', 'invoice_manager') NOT NULL DEFAULT 'customer_manager',
    PRIMARY KEY (id)
);

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    archived TINYINT(1) DEFAULT 0,
    PRIMARY KEY (id)
);

-- Table des factures
CREATE TABLE IF NOT EXISTS invoices (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,  -- Nouvelle colonne 'amount'
    status ENUM('paid', 'pending', 'cancelled') DEFAULT 'pending',  -- Nouvelle colonne 'status'
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    CONSTRAINT fk_invoice_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO admins (email, password, role) 
VALUES 
('superadmin@example.com', SHA2('superadminpassword', 256), 'super_admin'),
('customeradmin@example.com', SHA2('customeradminpassword', 256), 'customer_manager'),
('productadmin@example.com', SHA2('productadminpassword', 256), 'product_manager'),
('invoiceadmin@example.com', SHA2('invoiceadminpassword', 256), 'invoice_manager');

INSERT INTO users (username, email, phone, password) 
VALUES 
('User1', 'user1@example.com', '1234567890', SHA2('password1', 256)),
('User2', 'user2@example.com', '2345678901', SHA2('password2', 256)),
('User3', 'user3@example.com', '3456789012', SHA2('password3', 256)),
('User4', 'user4@example.com', '4567890123', SHA2('password4', 256)),
('User5', 'user5@example.com', '5678901234', SHA2('password5', 256)),
('User6', 'user6@example.com', '6789012345', SHA2('password6', 256)),
('User7', 'user7@example.com', '7890123456', SHA2('password7', 256)),
('User8', 'user8@example.com', '8901234567', SHA2('password8', 256)),
('User9', 'user9@example.com', '9012345678', SHA2('password9', 256)),
('User10', 'user10@example.com', '0123456789', SHA2('password10', 256)),
('User11', 'user11@example.com', '1122334455', SHA2('password11', 256)),
('User12', 'user12@example.com', '2233445566', SHA2('password12', 256)),
('User13', 'user13@example.com', '3344556677', SHA2('password13', 256)),
('User14', 'user14@example.com', '4455667788', SHA2('password14', 256)),
('User15', 'user15@example.com', '5566778899', SHA2('password15', 256)),
('User16', 'user16@example.com', '6677889900', SHA2('password16', 256)),
('User17', 'user17@example.com', '7788990011', SHA2('password17', 256)),
('User18', 'user18@example.com', '8899001122', SHA2('password18', 256)),
('User19', 'user19@example.com', '9900112233', SHA2('password19', 256)),
('User20', 'user20@example.com', '1001223344', SHA2('password20', 256));


INSERT INTO products (name, description, price, image, archived) VALUES
('AIR FORCE', 'Les sneakers AIR FORCE noires allient confort et style classique. Conçues pour offrir un look urbain et une grande durabilité, elles sont parfaites pour une utilisation quotidienne. Avec une semelle robuste et un design élégant, ces chaussures sont idéales pour compléter vos tenues modernes. Disponibles au prix de 119$.', 119.00, '../assets/images/product1.png', 0),
('Sneakers Crater Impact CW2386 002 Gris', 'Les Sneakers Crater Impact CW2386 002 en gris offrent un style moderne et une performance exceptionnelle. Conçues avec des matériaux durables et une semelle en mousse recyclée, elles allient confort, légèreté et respect de l\'environnement. Leur design unique et leur couleur grise s\'adaptent parfaitement à toutes vos tenues, que ce soit pour une journée décontractée ou un look sportif. Disponibles au prix de 119$.', 119.00, '../assets/images/product2.png', 0);



INSERT INTO invoices (user_id, total, amount, status) VALUES
(1, 120.50, 120.50, 'paid'),
(2, 89.99, 89.99, 'pending'),
(3, 200.00, 200.00, 'paid'),
(4, 50.00, 50.00, 'cancelled'),
(5, 300.00, 300.00, 'pending'),
(6, 150.75, 150.75, 'paid'),
(7, 250.00, 250.00, 'pending'),
(8, 175.00, 175.00, 'paid'),
(9, 100.25, 100.25, 'pending'),
(10, 60.00, 60.00, 'cancelled');
