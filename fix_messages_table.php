<?php
require_once 'config/database.php';

try {
    // First, show us what tables exist
    echo "Current tables in database:<br>";
    $result = $pdo->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "<br>";
    }
    
    // Check if messages table exists
    $result = $pdo->query("SHOW TABLES LIKE 'messages'");
    if ($result->rowCount() == 0) {
        echo "<br>Messages table does not exist. Creating it...<br>";
        
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
        echo "<br>Messages table exists. Checking structure...<br>";
        
        // Show current structure
        $result = $pdo->query("DESCRIBE messages");
        echo "Current structure:<br>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "Field: " . $row['Field'] . "<br>";
        }
        
        // Check if product_id exists
        $result = $pdo->query("SHOW COLUMNS FROM messages LIKE 'product_id'");
        if ($result->rowCount() == 0) {
            echo "<br>Adding product_id column...<br>";
            
            // Add product_id column
            $pdo->exec("ALTER TABLE messages ADD COLUMN product_id INT NOT NULL");
            $pdo->exec("ALTER TABLE messages ADD FOREIGN KEY (product_id) REFERENCES products(id)");
            echo "product_id column added successfully.<br>";
        }
    }
    
    // Verify the final structure
    echo "<br>Final structure of messages table:<br>";
    $result = $pdo->query("DESCRIBE messages");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "Field: " . $row['Field'] . "<br>";
        echo "Type: " . $row['Type'] . "<br>";
        echo "Null: " . $row['Null'] . "<br>";
        echo "Key: " . $row['Key'] . "<br>";
        echo "Default: " . $row['Default'] . "<br>";
        echo "Extra: " . $row['Extra'] . "<br><br>";
    }
    
    echo "<br>Database structure verified and fixed.<br>";
    echo "You can now <a href='products.php'>view products</a> and send messages.";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 