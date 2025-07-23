<?php
session_start();
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Mini OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .terms-section {
            background-color: #f8f9fa;
            padding: 4rem 0;
        }
        .terms-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .terms-card:hover {
            transform: translateY(-5px);
        }
        .terms-icon {
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo.png" alt="Mini OLX Logo" style="height:32px; margin-right:8px;">
                Mini OLX
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

    <div class="terms-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="display-4 mb-4">Terms of Service</h1>
                    <p class="lead">By using Mini OLX, you agree to the following terms:</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card terms-card h-100">
                        <div class="card-body">
                            <i class="fas fa-user-shield terms-icon"></i>
                            <h3 class="card-title">Account Responsibility</h3>
                            <p class="card-text">Users are responsible for maintaining the confidentiality of their login credentials.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card terms-card h-100">
                        <div class="card-body">
                            <i class="fas fa-ban terms-icon"></i>
                            <h3 class="card-title">Prohibited Listings</h3>
                            <p class="card-text">Items that are illegal, counterfeit, or restricted by law are strictly prohibited.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card terms-card h-100">
                        <div class="card-body">
                            <i class="fas fa-copyright terms-icon"></i>
                            <h3 class="card-title">Content Ownership</h3>
                            <p class="card-text">Users retain ownership of their posted content but grant Mini OLX a license to display and distribute it on the platform.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card terms-card h-100">
                        <div class="card-body">
                            <i class="fas fa-gavel terms-icon"></i>
                            <h3 class="card-title">Dispute Resolution</h3>
                            <p class="card-text">Mini OLX is not liable for disputes between users but will assist in mediation where possible.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card terms-card">
                        <div class="card-body">
                            <i class="fas fa-database terms-icon"></i>
                            <h3 class="card-title">Data Usage</h3>
                            <p class="card-text">Your data is securely stored and used only for platform-related operations. Please review our Privacy Policy for more details.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="lead">By using our platform, you agree to these terms.</p>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="register.php" class="btn btn-primary btn-lg me-3">Register Now</a>
                    <?php endif; ?>
                    <a href="products.php" class="btn btn-outline-primary btn-lg">Browse Products</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 