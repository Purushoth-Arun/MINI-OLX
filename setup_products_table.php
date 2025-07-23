<?php
require_once 'config/database.php';

try {
    // Drop existing products table if it exists
    $pdo->exec("DROP TABLE IF EXISTS products");
    
    // Create products table
    $sql = "CREATE TABLE products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255),
        category_id INT,
        seller_id INT NOT NULL,
        status ENUM('active', 'sold', 'pending', 'rejected') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id),
        FOREIGN KEY (seller_id) REFERENCES users(id)
    )";
    
    $pdo->exec($sql);
    echo "Products table created successfully with all required columns.<br>";
    echo "You can now <a href='add_product.php'>add products</a>.";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 