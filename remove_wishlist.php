<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$product_id = $_GET['id'];

// Remove from wishlist
$stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
if ($stmt->execute([$_SESSION['user_id'], $product_id])) {
    header('Location: dashboard.php#wishlist');
} else {
    header('Location: dashboard.php#wishlist&error=failed_to_remove');
}
exit();
?> 