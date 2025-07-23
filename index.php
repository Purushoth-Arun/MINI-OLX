<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini OLX - Buy and Sell Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 3.5rem;
            color: #2c3e50;
        }
        .welcome-subtitle {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 1.5rem;
            color: #34495e;
        }
        .welcome-text {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            line-height: 1.6;
            color: #2c3e50;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .feature-text {
            font-size: 1rem;
            line-height: 1.5;
            color: #34495e;
            margin-bottom: 1.5rem;
        }
        .card-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #2c3e50;
        }
        .card-text {
            font-family: 'Poppins', sans-serif;
            color: #34495e;
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
                        <a class="nav-link" href="categories.php">Categories</a>
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

    <div class="container mt-4">
        <div class="jumbotron text-center">
            <img src="assets/images/logo.png" alt="Mini OLX Logo" style="height:100px; display:block; margin:0 auto 20px auto;">
            <h1 class="display-4 welcome-title">Welcome to Mini OLX</h1>
            <p class="lead welcome-subtitle">Your one-stop platform for buying and selling products.</p>
            <hr class="my-4">
            <p class="welcome-text">Join our community of buyers and sellers today!</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a class="btn btn-primary btn-lg" href="register.php" role="button">Get Started</a>
            <?php endif; ?>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body">
                        <h5 class="feature-title">Buy Products</h5>
                        <p class="feature-text">Browse through thousands of products from trusted sellers.</p>
                        <a href="products.php" class="btn btn-primary">Browse Products</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body">
                        <h5 class="feature-title">Sell Products</h5>
                        <p class="feature-text">List your products and reach thousands of potential buyers.</p>
                        <a href="add_product.php" class="btn btn-primary">Sell Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body">
                        <h5 class="feature-title">Safe & Secure</h5>
                        <p class="feature-text">Our platform ensures safe transactions and secure payments.</p>
                        <a href="secure.php" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6 text-center mb-3">
                    <img src="assets/images/logo.png" alt="Mini OLX Logo" style="height:60px; display:block; margin:0 auto 10px auto;">
                    <h5>About Mini OLX</h5>
                    <p>A simple and secure platform for buying and selling products.</p>
                </div>
                <div class="col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-white">About Us</a></li>
                        <li><a href="contact.php" class="text-white">Contact</a></li>
                        <li><a href="terms.php" class="text-white">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 