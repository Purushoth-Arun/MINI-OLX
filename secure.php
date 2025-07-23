<?php
session_start();
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety & Security - Mini OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .security-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .security-card {
            transition: transform 0.3s ease;
            height: 100%;
        }
        .security-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/logo.png" alt="Mini OLX Logo" style="height:40px; margin-right:8px;">
                <span style="font-weight:bold; font-size:1.5rem;">Mini OLX</span>
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

    <div class="container mt-5">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 mb-3">Your Trust, Our Priority</h1>
                <p class="lead">At Mini OLX, we understand the importance of safety and trust in online buying and selling. Our platform is designed with multiple layers of security to protect both buyers and sellers at every step of the transaction process.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card security-card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-shield security-icon"></i>
                        <h3 class="card-title">Secure User Authentication</h3>
                        <p class="card-text">All user accounts are protected with secure login credentials. We also implement password hashing and offer session timeouts to prevent unauthorized access.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card security-card">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle security-icon"></i>
                        <h3 class="card-title">Verified Listings & Sellers</h3>
                        <p class="card-text">To ensure a trustworthy marketplace, our platform verifies sellers and monitors product listings. Suspicious activity or reported users are immediately reviewed and addressed.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card security-card">
                    <div class="card-body text-center">
                        <i class="fas fa-lock security-icon"></i>
                        <h3 class="card-title">Encrypted Data Transmission</h3>
                        <p class="card-text">All personal and transactional data is transmitted over secure, encrypted channels using industry-standard SSL (Secure Socket Layer) technology.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card security-card">
                    <div class="card-body text-center">
                        <i class="fas fa-comments security-icon"></i>
                        <h3 class="card-title">Buyer-Seller Communication</h3>
                        <p class="card-text">Our integrated messaging system ensures that all communication between buyers and sellers happens securely within the platform—reducing risks of scams or phishing.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card security-card">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt security-icon"></i>
                        <h3 class="card-title">Fraud Prevention Measures</h3>
                        <p class="card-text">We continuously monitor user activity and employ anti-fraud algorithms to detect unusual behavior or potentially fraudulent listings.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card security-card">
                    <div class="card-body text-center">
                        <i class="fas fa-headset security-icon"></i>
                        <h3 class="card-title">Transparency & Support</h3>
                        <p class="card-text">Each transaction comes with detailed records and receipts. Our support team is always available to assist with any disputes or issues.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 mb-5">
            <div class="col-12 text-center">
                <p class="lead">Ready to start buying and selling safely?</p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-primary btn-lg me-3">Register Now</a>
                <?php endif; ?>
                <a href="products.php" class="btn btn-outline-primary btn-lg">Browse Products</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 