<?php
session_start();
require_once 'config/database.php';

// Get category filter if set
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Build the query to show all active products
$query = "SELECT p.*, c.name as category_name, u.username as seller_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          JOIN users u ON p.seller_id = u.id 
          WHERE p.status = 'active' 
          AND c.name NOT IN ('Services', 'Real Estate', 'Jobs')";

$params = [];

// Add category filter if specified
if ($category_id) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
}

// Add search filter if specified
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $search_term = '%' . $_GET['search'] . '%';
    $params[] = $search_term;
    $params[] = $search_term;
}

// Add price range filters if specified
if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
    $query .= " AND p.price >= ?";
    $params[] = (float)$_GET['min_price'];
}

if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
    $query .= " AND p.price <= ?";
    $params[] = (float)$_GET['max_price'];
}

$query .= " ORDER BY p.created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $products = [];
}

// Get all categories except Services, Real Estate, and Jobs for the filter dropdown
$stmt = $pdo->prepare("SELECT * FROM categories WHERE name NOT IN ('Services', 'Real Estate', 'Jobs') ORDER BY name");
$stmt->execute();
$categories = $stmt->fetchAll();

// Debug information
if (empty($products)) {
    error_log("No products found. Query: " . $query);
    error_log("Parameters: " . print_r($params, true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Mini OLX</title>
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
                        <h5>Filters</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="mb-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="min_price" class="form-label">Min Price</label>
                                <input type="number" class="form-control" id="min_price" name="min_price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="max_price" class="form-label">Max Price</label>
                                <input type="number" class="form-control" id="max_price" name="max_price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="row">
                    <?php if (empty($products)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <?php if (isset($_GET['search']) || isset($_GET['category']) || isset($_GET['min_price']) || isset($_GET['max_price'])): ?>
                                    No products found matching your search criteria. Try adjusting your filters.
                                <?php else: ?>
                                    No products available at the moment. Please check back later.
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <?php if ($product['image']): ?>
                                        <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light text-center py-5">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Category: <?php echo htmlspecialchars($product['category_name']); ?><br>
                                                Seller: <?php echo htmlspecialchars($product['seller_name']); ?>
                                            </small>
                                        </p>
                                        <h6 class="card-subtitle mb-2 text-primary">₹<?php echo number_format($product['price'], 2); ?></h6>
                                    </div>
                                    <div class="card-footer">
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary mb-2">View Details</a>
                                        <?php else: ?>
                                            <a href="register.php?redirect=product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary mb-2">View Details</a>
                                        <?php endif; ?>
                                        <br>
                                        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $product['seller_id']): ?>
                                            <a href="add_wishlist.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary">Add to Wishlist</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 