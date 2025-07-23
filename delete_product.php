<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if product ID is provided
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$product_id = $_GET['id'];

try {
    // First verify that the product belongs to the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
    $stmt->execute([$product_id, $_SESSION['user_id']]);
    $product = $stmt->fetch();

    if (!$product) {
        $_SESSION['error'] = "Product not found or you don't have permission to delete it.";
        header('Location: dashboard.php');
        exit();
    }

    // Delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->execute([$product_id, $_SESSION['user_id']]);

    // Delete associated image if exists
    if ($product['image'] && file_exists($product['image'])) {
        unlink($product['image']);
    }

    $_SESSION['success'] = "Product deleted successfully!";
} catch (PDOException $e) {
    $_SESSION['error'] = "Error deleting product: " . $e->getMessage();
}

header('Location: dashboard.php');
exit();
?> 