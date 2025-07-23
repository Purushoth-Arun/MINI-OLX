<?php
require_once 'config/database.php';

try {
    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Drop the messages table if it exists
    $pdo->exec("DROP TABLE IF EXISTS messages");
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Create the messages table with the correct structure
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
    
    // Verify the table was created correctly
    $result = $pdo->query("DESCRIBE messages");
    echo "Messages table structure:<br>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "Field: " . $row['Field'] . "<br>";
        echo "Type: " . $row['Type'] . "<br>";
        echo "Null: " . $row['Null'] . "<br>";
        echo "Key: " . $row['Key'] . "<br>";
        echo "Default: " . $row['Default'] . "<br>";
        echo "Extra: " . $row['Extra'] . "<br><br>";
    }
    
    echo "Messages table has been rebuilt successfully!<br>";
    echo "You can now <a href='products.php'>view products</a> and send messages.";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 