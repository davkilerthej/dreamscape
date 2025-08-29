<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item'];
    $password = $_POST['password'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'dreamscape');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Verify password
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($password, $hashed_password)) {
        $_SESSION['error'] = "Invalid password. Please try again.";
        header("Location: inventory.php");
        exit;
    }

    // Update the purchase status to refunded
    $stmt = $conn->prepare("UPDATE purchases SET status = 'refunded', refund_date = NOW() WHERE username = ? AND item = ? AND status = 'active'");
    $stmt->bind_param('ss', $_SESSION['username'], $item);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Your " . $item . " subscription has been refunded successfully.";
    } else {
        $_SESSION['error'] = "Error processing refund. Please try again.";
    }

    $stmt->close();
    $conn->close();

    header("Location: inventory.php");
    exit;
}
?>