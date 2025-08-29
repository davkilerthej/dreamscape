<?php
session_start(); // Start session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'dreamscape');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check the username and password
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Login successful
            $_SESSION['username'] = $username; // Store username in session
            header("Location: index.php");
            exit;
        } else {
            header("Location: login.php?error=Invalid password");
            exit;
        }
    } else {
        header("Location: login.php?error=Username not found");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
