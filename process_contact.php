<?php
// Start the session and include necessary files
session_start();
include("connect.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $concern = mysqli_real_escape_string($conn, $_POST['concern']);
    
    // Insert into database
    $sql = "INSERT INTO contact_messages (email, phone, concern, created_at) 
            VALUES ('$email', '$phone', '$concern', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect back to about page with success message
        $_SESSION['contact_success'] = "Thank you for your message. We'll get back to you soon!";
    } else {
        // Redirect back to about page with error message
        $_SESSION['contact_error'] = "Sorry, there was an error sending your message. Please try again.";
    }
    
    header("Location: about.php");
    exit();
}
?> 