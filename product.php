<?php
session_start();
require_once 'config/database.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = $_GET['id'];

// Get product details
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, u.username as seller_name, u.id as seller_id 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    JOIN users u ON p.seller_id = u.id 
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit();
}

// Redirect non-logged-in users to register page
if (!isset($_SESSION['user_id'])) {
    header('Location: register.php?redirect=product.php?id=' . $product_id);
    exit();
}

$error = '';
$success = '';

// Handle purchase
if (isset($_POST['purchase']) && isset($_SESSION['user_id'])) {
    if ($_SESSION['user_id'] == $product['seller_id']) {
        $error = 'You cannot book your own product';
    } else {
        // Check if the product is already booked by the current user
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE buyer_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        $already_booked = $stmt->fetchColumn() > 0;
        if ($already_booked) {
            $error = 'You have already booked this product.';
        } else {
            try {
                $pdo->beginTransaction();
                
                // Update product status
                $stmt = $pdo->prepare("UPDATE products SET status = 'sold' WHERE id = ?");
                $stmt->execute([$product_id]);
                
                // Record booking (no payment)
                $stmt = $pdo->prepare("INSERT INTO purchases (buyer_id, product_id) VALUES (?, ?)");
                $stmt->execute([$_SESSION['user_id'], $product_id]);
                
                $pdo->commit();
                $success = 'Booked successfully!';
                // Send booking notification to the seller
                if ($_SESSION['user_id'] != $product['seller_id']) {
                    $notification = "I have booked your product: " . $product['name'];
                    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$_SESSION['user_id'], $product['seller_id'], $product_id, $notification]);
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Booking failed. Please try again.';
            }
        }
    }
}

// Handle unbooking
if (isset($_POST['unbook']) && isset($_SESSION['user_id'])) {
    // Remove booking
    $stmt = $pdo->prepare("DELETE FROM purchases WHERE buyer_id = ? AND product_id = ?");
    if ($stmt->execute([$_SESSION['user_id'], $product_id])) {
        // Optionally, set product status back to 'active'
        $stmt = $pdo->prepare("UPDATE products SET status = 'active' WHERE id = ?");
        $stmt->execute([$product_id]);
        $success = 'Booking cancelled successfully!';
        $already_booked = false;
        // Refresh product status in $product
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name, u.username as seller_name, u.id as seller_id 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            JOIN users u ON p.seller_id = u.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
    } else {
        $error = 'Failed to cancel booking. Please try again.';
    }
}

// Handle message
if (isset($_POST['send_message']) && isset($_SESSION['user_id'])) {
    $message = trim($_POST['message']);
    
    if (empty($message)) {
        $error = 'Message cannot be empty';
    } else {
        // Prevent duplicate messages: check if the same message (case-insensitive, trimmed) was already sent by this user for this product
        $normalized_message = strtolower(trim($message));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE sender_id = ? AND product_id = ? AND LOWER(TRIM(message)) = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id, $normalized_message]);
        $duplicate_count = $stmt->fetchColumn();
        if ($duplicate_count > 0) {
            $error = 'You have already sent this message.';
        } else {
            // Set receiver_id based on who is sending the message
            if ($_SESSION['user_id'] == $product['seller_id']) {
                // If seller is sending, find the last buyer who messaged
                $stmt = $pdo->prepare("
                    SELECT sender_id 
                    FROM messages 
                    WHERE product_id = ? AND sender_id != ? 
                    ORDER BY created_at DESC 
                    LIMIT 1
                ");
                $stmt->execute([$product_id, $_SESSION['user_id']]);
                $last_buyer = $stmt->fetch();
                $receiver_id = $last_buyer ? $last_buyer['sender_id'] : null;
            } else {
                // If buyer is sending, set receiver to seller
                $receiver_id = $product['seller_id'];
            }
            
            if ($receiver_id) {
                $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$_SESSION['user_id'], $receiver_id, $product_id, $message])) {
                    // Instead of redirecting, just set success message
                    $success = 'Message sent successfully!';
                    // Clear the message input
                    $_POST['message'] = '';
                } else {
                    $error = 'Failed to send message';
                }
            } else {
                $error = 'No recipient found for the message. Please wait for a buyer to message you first.';
            }
        }
    }
}

// Handle return
if (isset($_POST['return']) && isset($_SESSION['user_id'])) {
    try {
        $pdo->beginTransaction();
        
        // Check if the product is booked by the current user
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE buyer_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        $is_booked = $stmt->fetchColumn() > 0;
        
        if (!$is_booked) {
            $error = 'You have not booked this product.';
        } else {
            // Remove the booking
            $stmt = $pdo->prepare("DELETE FROM purchases WHERE buyer_id = ? AND product_id = ?");
            $stmt->execute([$_SESSION['user_id'], $product_id]);
            
            // Update product status back to active
            $stmt = $pdo->prepare("UPDATE products SET status = 'active' WHERE id = ?");
            $stmt->execute([$product_id]);
            
            // Send return notification to the seller
            $notification = "The buyer has returned your product: " . $product['name'];
            $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $product['seller_id'], $product_id, $notification]);
            
            $pdo->commit();
            $success = 'Product returned successfully!';
            
            // Refresh product status
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name, u.username as seller_name, u.id as seller_id 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                JOIN users u ON p.seller_id = u.id 
                WHERE p.id = ?
            ");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = 'Failed to return product. Please try again.';
    }
}

// Get messages if user is logged in
$messages = [];
$is_in_wishlist = false;
if (isset($_SESSION['user_id'])) {
    // Check if product is in wishlist
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    $is_in_wishlist = $stmt->fetch() ? true : false;

    // Get messages
    $messages_query = "SELECT m.*, u.username as sender_name 
                      FROM messages m 
                      JOIN users u ON m.sender_id = u.id 
                      WHERE m.product_id = ? 
                      ORDER BY m.created_at ASC";
    $stmt = $pdo->prepare($messages_query);
    $stmt->execute([$product_id]);
    $messages = $stmt->fetchAll();

    // Check if the current user has already booked this product
    $already_booked = false;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE buyer_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    $already_booked = $stmt->fetchColumn() > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Mini OLX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Mini OLX</a>
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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
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
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid mb-4" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php endif; ?>
                        
                        <h1 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                        <h4 class="text-primary mb-4">₹<?php echo number_format($product['price'], 2); ?></h4>
                        
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        
                        <div class="mt-4">
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
                            <p><strong>Seller:</strong> <?php echo htmlspecialchars($product['seller_name']); ?></p>
                            <?php
                            // Fetch seller phone number
                            $stmt = $pdo->prepare("SELECT phone FROM users WHERE id = ?");
                            $stmt->execute([$product['seller_id']]);
                            $seller = $stmt->fetch();
                            ?>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($seller['phone']); ?></p>
                            <p><strong>Status:</strong> <?php echo ucfirst($product['status'] ?? 'active'); ?></p>
                            <p><strong>Posted:</strong> <?php echo date('M d, Y', strtotime($product['created_at'])); ?></p>
                        </div>
                        
                        <?php if (isset($_SESSION['user_id']) && ($product['status'] == 'active' || empty($product['status']) || ($already_booked && $product['status'] == 'sold'))): ?>
                            <?php if ($_SESSION['user_id'] != $product['seller_id']): ?>
                                <?php if ($already_booked): ?>
                                    <div class="mt-4">
                                        <form method="POST" action="" class="d-inline">
                                            <button type="submit" name="unbook" class="btn btn-secondary btn-lg">Unbook</button>
                                        </form>
                                        <form method="POST" action="" class="d-inline">
                                            <button type="submit" name="return" class="btn btn-danger btn-lg ms-2">Return Product</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <form method="POST" action="" class="mt-4">
                                        <button type="submit" name="purchase" class="btn btn-primary btn-lg">Book Now</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Messages</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($messages)): ?>
                                <p>No messages yet.</p>
                            <?php else: ?>
                                <div class="messages" style="display: flex; flex-direction: column; gap: 10px;">
                                    <?php foreach ($messages as $message): ?>
                                        <div class="message <?php echo $message['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received'; ?>" 
                                             style="display: flex; <?php echo $message['sender_id'] == $_SESSION['user_id'] ? 'justify-content: flex-end;' : 'justify-content: flex-start;'; ?>">
                                            <div class="message-content" 
                                                 style="max-width: 70%; padding: 10px 15px; border-radius: 15px; 
                                                        <?php echo $message['sender_id'] == $_SESSION['user_id'] 
                                                            ? 'background-color: #007bff; color: white; margin-left: auto;' 
                                                            : 'background-color: #f1f1f1; color: black; margin-right: auto;'; ?>">
                                                <p style="margin: 0;"><?php echo htmlspecialchars($message['message']); ?></p>
                                                <small style="display: block; margin-top: 5px; opacity: 0.7; font-size: 0.8em;">
                                                    <?php echo htmlspecialchars($message['sender_name']); ?> - 
                                                    <?php echo date('M d, Y H:i', strtotime($message['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="" class="mt-3" id="messageForm">
                                <div class="input-group">
                                    <textarea class="form-control" name="message" rows="1" placeholder="Type your message..." required id="messageInput"></textarea>
                                    <button type="submit" name="send_message" class="btn btn-primary" id="sendMessageBtn">
                                        <i class="bi bi-send"></i> Send
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Seller Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($product['seller_name']); ?></p>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $product['seller_id']): ?>
                            <?php if ($is_in_wishlist): ?>
                                <button class="btn btn-secondary" disabled>Already in Wishlist</button>
                            <?php else: ?>
                                <a href="add_wishlist.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary">Add to Wishlist</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message) return;
            
            // Disable the send button while sending
            const sendButton = document.getElementById('sendMessageBtn');
            sendButton.disabled = true;
            
            // Create form data
            const formData = new FormData();
            formData.append('message', message);
            formData.append('send_message', '1');
            
            // Send message using AJAX
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Create a temporary div to parse the response
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Get the messages container from the response
                const newMessages = tempDiv.querySelector('.messages');
                if (newMessages) {
                    // Update the messages container
                    document.querySelector('.messages').innerHTML = newMessages.innerHTML;
                }
                
                // Clear the input
                messageInput.value = '';
                
                // Re-enable the send button
                sendButton.disabled = false;
                
                // Focus back on the input
                messageInput.focus();
            })
            .catch(error => {
                console.error('Error:', error);
                sendButton.disabled = false;
            });
        });
    </script>
</body>
</html> 