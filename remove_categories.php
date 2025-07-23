<?php
require_once 'config/database.php';

try {
    // Categories to remove
    $categoriesToRemove = ['Services', 'Real Estate', 'Jobs'];
    
    // Prepare the SQL statement
    $placeholders = str_repeat('?,', count($categoriesToRemove) - 1) . '?';
    $sql = "DELETE FROM categories WHERE name IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    
    // Execute the statement
    $stmt->execute($categoriesToRemove);
    
    // Check how many rows were affected
    $rowsAffected = $stmt->rowCount();
    
    echo "Successfully removed $rowsAffected categories: " . implode(', ', $categoriesToRemove);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 