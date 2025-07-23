<?php
require_once 'config/database.php';

try {
    // Drop messages table if it exists
    $pdo->exec("DROP TABLE IF EXISTS messages");
    
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
    echo "Messages table created successfully!<br>";
    echo "You can now <a href='products.php'>view products</a> and send messages.";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 