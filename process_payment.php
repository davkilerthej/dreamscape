<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$item = $_POST['item'];
$minecraftUsername = $_POST['username'];

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'dreamscape');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the price for the item
$prices = [
    'VIP' => 1.00,
    'Premium' => 5.00,
    'Ultimate' => 20.00
];
$price = $prices[$item] ?? 0.00;

// Check for existing purchase of the same item
$username = $_SESSION['username'];
$checkStmt = $conn->prepare("SELECT id FROM purchases WHERE username = ? AND item = ? AND status = 'active'");
$checkStmt->bind_param('ss', $username, $item);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $_SESSION['error'] = "You already have an active " . $item . " subscription.";
    header("Location: shop.php");
    exit;
}

$checkStmt->close();

// Fetch the user_id from the users table
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Process the payment (simulated)
$success = true; // In a real application, this would be determined by the payment processor

if ($success) {
    // Insert the purchase into the database
    $stmt = $conn->prepare("INSERT INTO purchases (user_id, username, minecraft_username, item, price, status, purchase_date) VALUES (?, ?, ?, ?, ?, 'active', NOW())");
    $stmt->bind_param('isssd', $user_id, $username, $minecraftUsername, $item, $price);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing - Dreamscape</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #1a233a, #4a5673);
            background-size: 400% 400%;
            color: white;
            line-height: 1.6;
            min-height: 100vh;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1em 2em;
            background: transparent;
            position: relative;
            z-index: 100;
        }

        nav a {
            color: white;
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            border: 2px solid transparent;
            border-radius: 20px;
            transition: all 0.3s ease-in-out;
            font-weight: 600;
        }

        nav a:hover {
            background: white;
            color: #1a233a;
            border-color: white;
        }

        .auth-links {
            display: flex;
            align-items: center;
        }

        main {
            padding: 3em 1em;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
        }

        .payment-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2.5em;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .payment-header {
            text-align: center;
            margin-bottom: 2em;
        }

        .payment-header h2 {
            font-size: 2em;
            margin-bottom: 0.5em;
            color: #fff;
        }

        .payment-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1em;
        }

        .payment-status {
            text-align: center;
            margin-bottom: 2em;
        }

        .status-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1em;
        }

        .status-icon.success {
            color: #4CAF50;
        }

        .status-icon.error {
            color: #ff6b6b;
        }

        .payment-details {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5em;
            border-radius: 15px;
            margin-bottom: 2em;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5em 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: rgba(255, 255, 255, 0.7);
        }

        .detail-value {
            color: #fff;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 1em;
            justify-content: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary:hover {
            background: #45a049;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .message {
            padding: 1em;
            border-radius: 10px;
            margin-bottom: 1.5em;
        }

        .success-message {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #4CAF50;
        }

        .error-message {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #ff6b6b;
        }

        /* Prevent scrolling when menu is open */
        body.menu-open {
            overflow: hidden;
        }

        /* Create overlay for mobile menu */
        .menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 89;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        body.menu-open .menu-overlay {
            display: block;
            opacity: 1;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            transition: all 0.3s ease-in-out;
        }

        /* Hamburger Menu Styling */
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            cursor: pointer;
            z-index: 200;
            position: relative;
        }

        .hamburger span {
            display: block;
            height: 3px;
            width: 100%;
            background-color: white;
            border-radius: 3px;
            transition: all 0.3s ease-in-out;
        }

        /* Hamburger animation */
        .hamburger.active span:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }

        /* Responsive styles */
        @media screen and (max-width: 768px) {
            .hamburger {
                display: flex;
                margin-right: 15px;
            }

            .nav-menu {
                position: fixed;
                left: -66.67%;
                top: 0;
                flex-direction: column;
                background-color: rgba(26, 35, 58, 0.95);
                width: 66.67%;
                height: 100vh;
                z-index: 90;
                text-align: center;
                justify-content: flex-start;
                transition: 0.3s;
                padding-top: 60px;
                box-shadow: 3px 0 15px rgba(0, 0, 0, 0.3);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .nav-menu.active {
                left: 0;
            }

            .nav-menu a {
                margin: 10px 0;
                font-size: 1.2rem;
                display: block;
                width: 100%;
                text-align: center;
                padding: 15px 0;
                border-radius: 0;
                border: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-menu a:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
                border-color: rgba(255, 255, 255, 0.1);
            }
            
            /* Mobile welcome text specific styling */
            .mobile-auth-links span {
                display: block;
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
                padding: 0 10px;
            }
            
            /* Only apply these styles to the mobile auth-links */
            .mobile-auth-links {
                display: flex;
                position: absolute;
                bottom: 40px;
                left: 0;
                width: 100%;
                flex-direction: column;
                align-items: center;
                text-align: center;
                z-index: 91;
                padding: 0 15px;
            }

            .mobile-auth-links a, .mobile-auth-links span {
                margin: 5px 0;
            }
            
            /* Hide desktop auth-links in mobile view */
            .desktop-auth-links {
                display: none;
            }
        }

        /* Desktop auth-links styling (default) */
        .desktop-auth-links {
            display: flex;
            align-items: center;
        }

        /* Hide mobile auth-links in desktop view */
        .mobile-auth-links {
            display: none;
        }
    </style>
</head>
<body>
    <div class="menu-overlay"></div>
    <nav>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="nav-menu">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <a href="news.php">News</a>
            <a href="shop.php">Shop</a>
            <a href="inventory.php">Inventory</a>
            
            <!-- Auth links inside mobile menu -->
            <div class="mobile-auth-links">
                <?php if (isset($_SESSION['username'])): ?>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="register.php">Register</a>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Auth links for desktop view -->
        <div class="desktop-auth-links">
            <?php if (isset($_SESSION['username'])): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        <div class="payment-container">
            <div class="payment-header">
                <h2>Payment Processing</h2>
                <p>Please wait while we process your payment</p>
            </div>

            <div class="payment-status">
                <?php if ($success): ?>
                    <svg class="status-icon success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <div class="message success-message">
                        Payment processed successfully! Your purchase has been completed.
                    </div>
                <?php else: ?>
                    <svg class="status-icon error" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    <div class="message error-message">
                        There was an error processing your payment. Please try again.
                    </div>
                <?php endif; ?>
            </div>

            <div class="payment-details">
                <div class="detail-item">
                    <span class="detail-label">Item:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($item); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Minecraft Username:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($minecraftUsername); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Price:</span>
                    <span class="detail-value">€<?php echo number_format($price, 2); ?></span>
                </div>
            </div>

            <div class="action-buttons">
                <?php if ($success): ?>
                    <a href="inventory.php" class="btn btn-primary">View Inventory</a>
                <?php endif; ?>
                <a href="shop.php" class="btn btn-secondary">Return to Shop</a>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hamburger = document.querySelector('.hamburger');
            const navMenu = document.querySelector('.nav-menu');
            const body = document.body;
            const overlay = document.querySelector('.menu-overlay');
            
            hamburger.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                hamburger.classList.toggle('active');
                body.classList.toggle('menu-open');
            });
            
            // Close menu when clicking on the overlay
            overlay.addEventListener('click', () => {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                body.classList.remove('menu-open');
            });
        });
    </script>
</body>
</html>