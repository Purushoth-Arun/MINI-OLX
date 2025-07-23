<?php
require_once 'config/database.php';

try {
    // Create categories table
    $sql = "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "Categories table created successfully.<br>";

    // Insert default categories
    $categories = [
        ['Electronics', 'Electronic devices and accessories'],
        ['Fashion', 'Clothing, shoes, and accessories'],
        ['Home & Garden', 'Furniture, decor, and garden items'],
        ['Vehicles', 'Cars, motorcycles, and other vehicles'],
        ['Sports & Hobbies', 'Sports equipment and hobby items'],
        ['Books & Education', 'Books, educational materials, and courses'],
        ['Services', 'Professional and personal services'],
        ['Real Estate', 'Properties for rent or sale'],
        ['Jobs', 'Job listings and opportunities'],
        ['Other', 'Other miscellaneous items']
    ];

    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    
    echo "Default categories inserted successfully.<br>";
    echo "Database setup completed. You can now <a href='add_product.php'>add products</a>.";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 