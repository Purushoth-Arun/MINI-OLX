<?php
session_start();
require_once 'config/database.php';

// Fetch all categories except Jobs, Real Estate, and Services
$stmt = $pdo->prepare("SELECT * FROM categories WHERE name NOT IN ('Jobs', 'Real Estate', 'Services')");
$stmt->execute();
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Mini OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .category-section {
            background-color: #f8f9fa;
            padding: 4rem 0;
        }
        .category-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/logo.png" alt="Mini OLX Logo" style="height:32px; margin-right:8px;">
                <span>Mini OLX</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="categories.php">Categories</a>
                    </li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="category-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="display-4 mb-4">Product Categories</h1>
                    <p class="lead">Browse products by category</p>
                </div>
            </div>

            <div class="row g-4">
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-4">
                        <div class="card category-card">
                            <div class="card-body text-center">
                                <?php
                                // Set appropriate icon based on category name
                                $icon = 'fa-tag'; // default icon
                                $categoryName = strtolower($category['name']);
                                
                                if (strpos($categoryName, 'electronics') !== false) {
                                    $icon = 'fa-laptop';
                                } elseif (strpos($categoryName, 'mobile') !== false) {
                                    $icon = 'fa-mobile-alt';
                                } elseif (strpos($categoryName, 'fashion') !== false) {
                                    $icon = 'fa-tshirt';
                                } elseif (strpos($categoryName, 'home') !== false) {
                                    $icon = 'fa-home';
                                } elseif (strpos($categoryName, 'vehicle') !== false) {
                                    $icon = 'fa-car';
                                } elseif (strpos($categoryName, 'book') !== false) {
                                    $icon = 'fa-book';
                                } elseif (strpos($categoryName, 'sport') !== false) {
                                    $icon = 'fa-futbol';
                                } elseif (strpos($categoryName, 'music') !== false) {
                                    $icon = 'fa-music';
                                } elseif (strpos($categoryName, 'pet') !== false) {
                                    $icon = 'fa-paw';
                                } elseif (strpos($categoryName, 'beauty') !== false) {
                                    $icon = 'fa-spa';
                                } elseif (strpos($categoryName, 'food') !== false) {
                                    $icon = 'fa-utensils';
                                } elseif (strpos($categoryName, 'health') !== false) {
                                    $icon = 'fa-heartbeat';
                                } elseif (strpos($categoryName, 'toy') !== false) {
                                    $icon = 'fa-gamepad';
                                } elseif (strpos($categoryName, 'art') !== false) {
                                    $icon = 'fa-palette';
                                } elseif (strpos($categoryName, 'garden') !== false) {
                                    $icon = 'fa-leaf';
                                } elseif (strpos($categoryName, 'office') !== false) {
                                    $icon = 'fa-briefcase';
                                } elseif (strpos($categoryName, 'jewelry') !== false) {
                                    $icon = 'fa-gem';
                                }
                                ?>
                                <i class="fas <?php echo $icon; ?> category-icon"></i>
                                <h3 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h3>
                                <p class="card-text">Browse all products in this category</p>
                                <a href="products.php?category=<?php echo $category['id']; ?>" class="btn btn-primary">View Products</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if(empty($categories)): ?>
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No categories found. Please add categories through the admin panel.
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 