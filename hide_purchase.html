<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['purchase_id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: inventory.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'dreamscape');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify the purchase belongs to the user and is refunded
$stmt = $conn->prepare("SELECT status FROM purchases WHERE id = ? AND username = ?");
$stmt->bind_param('is', $_POST['purchase_id'], $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$purchase = $result->fetch_assoc();

if (!$purchase || strtolower($purchase['status']) !== 'refunded') {
    $_SESSION['error'] = "Invalid purchase or purchase not refunded.";
    header("Location: inventory.php");
    exit;
}

// Update the purchase to be hidden
$updateStmt = $conn->prepare("UPDATE purchases SET hidden = 1 WHERE id = ? AND username = ?");
$updateStmt->bind_param('is', $_POST['purchase_id'], $_SESSION['username']);

if ($updateStmt->execute()) {
    $_SESSION['success'] = "Item removed from inventory.";
} else {
    $_SESSION['error'] = "Failed to remove item from inventory.";
}

$stmt->close();
$updateStmt->close();
$conn->close();

header("Location: inventory.php");
exit; 