<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_id'])) {
    die("User not found.");
}

$user_id = $_SESSION['user_id'];
$search_query = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$sort_by = isset($_GET["sort_by"]) ? $_GET["sort_by"] : "date_saved_desc";
$articles_per_page = 4;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $articles_per_page;

$valid_sorts = [
    "title_asc" => "title ASC",
    "date_saved_desc" => "id DESC",
    "published_desc" => "published_at DESC"
];

$order_by = $valid_sorts[$sort_by] ?? "id DESC";

// Fetch total number of articles for pagination
$total_query = "SELECT COUNT(*) as total FROM saved_articles WHERE user_id = ?";
$total_stmt = mysqli_prepare($conn, $total_query);
mysqli_stmt_bind_param($total_stmt, "i", $user_id);
mysqli_stmt_execute($total_stmt);
$total_result = mysqli_stmt_get_result($total_stmt);
$total_row = mysqli_fetch_assoc($total_result);
$total_articles = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_articles / $articles_per_page);

// Fetch saved articles with optional search filter
if ($search_query) {
    $articles_query = "SELECT * FROM saved_articles WHERE user_id = ? 
                       AND (title LIKE ? OR description LIKE ?) 
                       ORDER BY $order_by LIMIT ?, ?";
    $search_param = "%" . $search_query . "%";
    $stmt = mysqli_prepare($conn, $articles_query);
    mysqli_stmt_bind_param($stmt, "issii", $user_id, $search_param, $search_param, $offset, $articles_per_page);
} else {
    $articles_query = "SELECT * FROM saved_articles WHERE user_id = ? ORDER BY $order_by LIMIT ?, ?";
    $stmt = mysqli_prepare($conn, $articles_query);
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $offset, $articles_per_page);
}

mysqli_stmt_execute($stmt);
$articles_result = mysqli_stmt_get_result($stmt);

// Output the articles as HTML
if (mysqli_num_rows($articles_result) > 0) {
    while ($article = mysqli_fetch_assoc($articles_result)) {
        echo '<li class="list-group-item">';
        echo '<h4>' . htmlspecialchars($article["title"]) . '</h4>';
        echo '<p><strong>Published on:</strong> ' . date("F j, Y, g:i a", strtotime($article["published_at"])) . '</p>';
        echo '<p>' . htmlspecialchars($article["description"]) . '</p>';
        echo '<a href="' . htmlspecialchars($article["url"]) . '" target="_blank" class="btn btn-primary">Read More</a>';
        echo '<form method="POST" action="unsave_article.php" class="d-inline">';
        echo '<input type="hidden" name="article_id" value="' . $article['id'] . '">';
        echo '<button type="submit" class="btn btn-danger">Unsave</button>';
        echo '</form>';
        echo '</li>';
    }
} else {
    echo '<p>No saved articles found.</p>';
}

// Optionally, you can return the current page number and total pages in a separate AJAX response if needed
// echo json_encode(['current_page' => $current_page, 'total_pages' => $total_pages]);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>