<?php
require_once 'config/database.php';

try {
    // Show all tables in the database
    echo "All tables in the database:<br>";
    $result = $pdo->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "<br>";
    }
    
    echo "<br>Structure of messages table:<br>";
    $result = $pdo->query("DESCRIBE messages");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "Field: " . $row['Field'] . "<br>";
        echo "Type: " . $row['Type'] . "<br>";
        echo "Null: " . $row['Null'] . "<br>";
        echo "Key: " . $row['Key'] . "<br>";
        echo "Default: " . $row['Default'] . "<br>";
        echo "Extra: " . $row['Extra'] . "<br><br>";
    }
    
    // Check if messages table exists
    $result = $pdo->query("SHOW TABLES LIKE 'messages'");
    if ($result->rowCount() == 0) {
        echo "Messages table does not exist!<br>";
        echo "Creating messages table...<br>";
        
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
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 