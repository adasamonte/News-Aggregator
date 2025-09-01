<?php
session_start();
include("connect.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not found."); // Or redirect to login page
}

// Get user ID directly from session
$user_id = $_SESSION['user_id'];

// Get article data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $url = $_POST["url"];
    $published_at = $_POST["published_at"];
    $description = $_POST["description"];

    // Insert into database
    $query = "INSERT INTO saved_articles (user_id, title, url, published_at, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $title, $url, $published_at, $description);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Article saved successfully!";
        header("Location: profile.php"); // Redirect back to profile
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "Error saving article.";
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
