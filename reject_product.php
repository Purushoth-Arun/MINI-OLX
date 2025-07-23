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

// Verify product exists and belongs to user
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$product_id, $_SESSION['user_id']]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: dashboard.php?error=Product not found');
    exit();
}

// Update product status to rejected
$stmt = $pdo->prepare("UPDATE products SET status = 'rejected' WHERE id = ?");
if ($stmt->execute([$product_id])) {
    header('Location: dashboard.php?success=Product rejected successfully');
} else {
    header('Location: dashboard.php?error=Failed to reject product');
}
exit(); 