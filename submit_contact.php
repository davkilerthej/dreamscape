<?php
// Database configuration
$host = 'localhost'; // Update if your DB is hosted elsewhere
$dbname = 'contact_form'; // Name of your database
$username = 'root'; // Your DB username
$password = ''; // Your DB password

// Email configuration (optional)
$sendEmail = true; // Set to false if you don't want email notifications
$recipientEmail = 'your-email@gmail.com'; // Replace with your email
$subject = "New Contact Form Submission";

// Process the form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Check for empty fields
    if (empty($name) || empty($email) || empty($message)) {
        die('Please fill in all required fields.');
    }

    try {
        // Save to database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO submissions (name, email, message) VALUES (:name, :email, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        // Optional: Send an email notification
        if ($sendEmail) {
            $emailBody = "You have received a new message:\n\n";
            $emailBody .= "Name: $name\n";
            $emailBody .= "Email: $email\n";
            $emailBody .= "Message:\n$message\n";

            mail($recipientEmail, $subject, $emailBody, "From: $email");
        }

        // Redirect or display a success message
        header("Location: thank_you.php");
        exit;
    } catch (PDOException $e) {
        die("Error saving data: " . $e->getMessage());
    }
} else {
    // Redirect to the form page if accessed directly
    header("Location: contact.php");
    exit;
}
?>
