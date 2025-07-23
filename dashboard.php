<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Get user's products
$stmt = $pdo->prepare("SELECT * FROM products WHERE seller_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll();

// Get user's wishlist
$stmt = $pdo->prepare("
    SELECT p.* 
    FROM products p
    JOIN wishlist w ON p.id = w.product_id
    WHERE w.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$wishlist = $stmt->fetchAll();

// Get user's purchases
$stmt = $pdo->prepare("
    SELECT p.*, pu.purchase_date 
    FROM products p
    JOIN purchases pu ON p.id = pu.product_id
    WHERE pu.buyer_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$purchases = $stmt->fetchAll();

if (isset($_POST['update_profile'])) {
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']);
    // Add more fields as needed

    $stmt = $pdo->prepare("UPDATE users SET email = ?, phone = ? WHERE id = ?");
    if ($stmt->execute([$new_email, $new_phone, $_SESSION['user_id']])) {
        $success = "Profile updated successfully!";
        // Refresh user info
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    } else {
        $error = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mini OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
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

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5>User Menu</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#my-products">My Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#wishlist">Wishlist</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#purchases">Booking History</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h5>
                    </div>
                    <div class="card-body">
                        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>
                        <p>Member since: <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editProfileForm">Edit Profile</button>
                        <div class="collapse mt-3" id="editProfileForm">
                            <form method="POST" action="">
                                <div class="mb-2">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-success btn-sm">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="my-products" class="card mb-4">
                    <div class="card-header">
                        <h5>My Products</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($products) > 0): ?>
                            <div class="row">
                                <?php foreach ($products as $product): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                                <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                                <p class="card-text"><strong>₹<?php echo number_format($product['price'], 2); ?></strong></p>
                                                <p class="card-text">
                                                    <small class="text-muted">
                                                        Status: 
                                                        <?php if ($product['status'] == 'active'): ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php elseif ($product['status'] == 'sold'): ?>
                                                            <span class="badge bg-danger">Sold</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary"><?php echo ucfirst($product['status']); ?></span>
                                                        <?php endif; ?>
                                                    </small>
                                                </p>
                                                <div class="d-flex flex-column gap-2">
                                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $product['seller_id']): ?>
                                                        <?php if ($is_in_wishlist): ?>
                                                            <button class="btn btn-secondary" disabled>In Wishlist</button>
                                                        <?php else: ?>
                                                            <a href="add_wishlist.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary">Add to Wishlist</a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                                                    <button class="btn btn-outline-danger" onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>You haven't listed any products yet.</p>
                            <a href="add_product.php" class="btn btn-primary">List a Product</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="wishlist" class="card mb-4">
                    <div class="card-header">
                        <h5>My Wishlist</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($wishlist) > 0): ?>
                            <div class="row">
                                <?php foreach ($wishlist as $product): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                                <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                                <p class="card-text"><strong>₹<?php echo number_format($product['price'], 2); ?></strong></p>
                                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                                                <a href="remove_wishlist.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>Your wishlist is empty.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="purchases" class="card mb-4">
                    <div class="card-header">
                        <h5>Booking History</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($purchases) > 0): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Booking Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($purchases as $purchase): ?>
                                            <tr>
                                                <td>
                                                    <a href="product.php?id=<?php echo $purchase['id']; ?>">
                                                        <?php echo htmlspecialchars($purchase['name']); ?>
                                                    </a>
                                                </td>
                                                <td>₹<?php echo number_format($purchase['price'], 2); ?></td>
                                                <td><?php echo date('F j, Y', strtotime($purchase['purchase_date'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>You haven't booked any products yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        function acceptProduct(productId) {
            if (confirm('Are you sure you want to accept this product?')) {
                window.location.href = 'accept_product.php?id=' + productId;
            }
        }

        function rejectProduct(productId) {
            if (confirm('Are you sure you want to reject this product?')) {
                window.location.href = 'reject_product.php?id=' + productId;
            }
        }

        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                window.location.href = 'delete_product.php?id=' + productId;
            }
        }
    </script>
</body>
</html> 