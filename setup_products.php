<?php
require_once 'config/database.php';

try {
    // Add category_id column to products table
    $sql = "ALTER TABLE products
            ADD COLUMN category_id INT,
            ADD FOREIGN KEY (category_id) REFERENCES categories(id)";
    
    $pdo->exec($sql);
    echo "Category_id column added to products table successfully.<br>";
    echo "You can now <a href='add_product.php'>add products</a> with categories.";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 