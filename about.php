<?php
session_start();
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Mini OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .about-section {
            background-color: #f8f9fa;
            padding: 4rem 0;
        }
        .mission-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .mission-card:hover {
            transform: translateY(-5px);
        }
        .about-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 3rem;
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        .about-text {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #34495e;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }
        .section-text {
            font-family: 'Poppins', sans-serif;
            color: #34495e;
            line-height: 1.6;
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

    <div class="about-section">
        <div class="container">
            <div class="container mt-5">
                <div class="text-center mb-4">
                    <img src="assets/images/logo.png" alt="Mini OLX Logo" style="height:100px; display:block; margin:0 auto 20px auto;">
                    <h1 class="display-4">About Mini OLX</h1>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12 text-center">
                    <p class="lead">Welcome to Mini OLX – a simple, secure, and user-friendly platform where anyone can buy or sell products with confidence.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card mission-card h-100">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Our Mission</h3>
                            <p class="card-text">Our mission is to empower individuals and small businesses to connect, trade, and grow within a trusted digital marketplace.</p>
                            <p class="card-text">We're committed to ensuring transparency, safety, and a seamless experience for all our users.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mission-card h-100">
                        <div class="card-body">
                            <h3 class="card-title mb-4">What We Offer</h3>
                            <p class="card-text">Whether you're looking to sell an unused item or find a great deal on a product, Mini OLX makes it easy with:</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Intuitive features</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Secure transactions</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Supportive community</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="lead">Ready to join our community?</p>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="register.php" class="btn btn-primary me-2">Register Now</a>
                    <?php endif; ?>
                    <a href="products.php" class="btn btn-outline-primary">Browse Products</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 