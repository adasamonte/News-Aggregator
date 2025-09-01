<?php
session_start();
include("connect.php");

// Ensure the user is logged in
if (!isset($_SESSION["username"])) {
    die("User not logged in.");
}

// Get user ID
$username = $_SESSION["username"];
$user_query = "SELECT ID FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$user_result = mysqli_stmt_get_result($stmt);

if ($user_row = mysqli_fetch_assoc($user_result)) {
    $user_id = $user_row["ID"];
} else {
    die("User not found.");
}

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
    } else {
        echo "Error saving article.";
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
