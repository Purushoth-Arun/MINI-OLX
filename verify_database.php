<?php
require_once 'config/database.php';

try {
    // First, let's check if the messages table exists
    $result = $pdo->query("SHOW TABLES LIKE 'messages'");
    if ($result->rowCount() == 0) {
        echo "Messages table does not exist. Creating it...<br>";
        
        // Create messages table
        $sql = "CREATE TABLE messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            product_id INT NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users(id),
            FOREIGN KEY (receiver_id) REFERENCES users(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )";
        
        $pdo->exec($sql);
        echo "Messages table created successfully.<br>";
    } else {
        echo "Messages table exists. Checking structure...<br>";
        
        // Check if product_id column exists
        $result = $pdo->query("SHOW COLUMNS FROM messages LIKE 'product_id'");
        if ($result->rowCount() == 0) {
            echo "product_id column does not exist. Adding it...<br>";
            
            // Add product_id column
            $pdo->exec("ALTER TABLE messages ADD COLUMN product_id INT NOT NULL");
            $pdo->exec("ALTER TABLE messages ADD FOREIGN KEY (product_id) REFERENCES products(id)");
            echo "product_id column added successfully.<br>";
        }
    }
    
    // Verify other required tables
    $tables = ['users', 'categories', 'products', 'wishlist', 'purchases'];
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() == 0) {
            echo "Table '$table' is missing. Please run setup_all_tables.php first.<br>";
        }
    }
    
    echo "<br>Database verification complete.<br>";
    echo "You can now <a href='products.php'>view products</a>.";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 