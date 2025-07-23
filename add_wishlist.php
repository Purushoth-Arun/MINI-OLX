<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = $_GET['id'];

// Check if product exists
$stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$stmt->execute([$product_id]);
if (!$stmt->fetch()) {
    header('Location: products.php');
    exit();
}

// Check if already in wishlist
$stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->execute([$_SESSION['user_id'], $product_id]);
if ($stmt->fetch()) {
    header('Location: product.php?id=' . $product_id);
    exit();
}

// Add to wishlist
$stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
if ($stmt->execute([$_SESSION['user_id'], $product_id])) {
    header('Location: product.php?id=' . $product_id . '&success=added_to_wishlist');
} else {
    header('Location: product.php?id=' . $product_id . '&error=failed_to_add_wishlist');
}
exit();
?> 