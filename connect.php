<?php
// Database connection settings
$host = 'localhost';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password (empty)
$database = 'newsagg';  // Your database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set character set to utf8mb4 for better compatibility with emojis and special characters
mysqli_set_charset($conn, "utf8mb4");

?>