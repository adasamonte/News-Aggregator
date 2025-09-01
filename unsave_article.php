<?php
session_start();
include("connect.php");

// Ensure user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION["email"]; // Use email to fetch user ID

// Fetch user ID
$user_query = "SELECT ID FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$user_result = mysqli_stmt_get_result($stmt);

if ($user_row = mysqli_fetch_assoc($user_result)) {
    $user_id = $user_row["ID"];
} else {
    die("User not found.");
}

// Check if article ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["article_id"])) {
    $article_id = $_POST["article_id"];

    // Delete the article from saved_articles
    $delete_query = "DELETE FROM saved_articles WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Article removed successfully!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Error removing article.'); window.location='profile.php';</script>";
    }
}
?>
