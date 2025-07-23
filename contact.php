<?php
session_start();
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Mini OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .contact-section {
            background-color: #f8f9fa;
            padding: 4rem 0;
        }
        .contact-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-5px);
        }
        .contact-icon {
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .contact-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 3rem;
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        .contact-text {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #34495e;
        }
        .form-label {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            color: #2c3e50;
        }
        .form-control {
            font-family: 'Poppins', sans-serif;
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

    <div class="contact-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="display-4 mb-4">Contact Us</h1>
                    <p class="lead">Have a question or need help? We're here for you!</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card contact-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-envelope contact-icon"></i>
                            <h3 class="card-title">Email</h3>
                            <p class="card-text">olx2025panel@gmail.com</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card contact-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                            <h3 class="card-title">Address</h3>
                            <p class="card-text">Tamil Nadu, India</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card contact-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clock contact-icon"></i>
                            <h3 class="card-title">Working Hours</h3>
                            <p class="card-text">Monday to Friday</p>
                            <p class="card-text">9:00 AM to 6:00 PM IST</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="lead">We strive to respond to all queries within 24 hours.</p>
                    <a href="mailto:olx2025panel@gmail.com" class="btn btn-primary btn-lg">Send Email</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 