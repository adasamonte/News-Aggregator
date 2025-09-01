<?php
session_start();
include("connect.php");

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// Fetch user ID
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

// Search functionality
$search_query = isset($_GET["search"]) ? trim($_GET["search"]) : "";

// Sorting functionality
$sort_by = isset($_GET["sort_by"]) ? $_GET["sort_by"] : "date_saved_desc"; // Default to most recently saved

$valid_sorts = [
    "title_asc" => "title ASC",
    "date_saved_desc" => "id DESC", // Assuming "id" represents the order of saving
    "published_desc" => "published_at DESC"
];

$order_by = $valid_sorts[$sort_by] ?? "id DESC"; // Default sorting

// Fetch saved articles with optional search filter
if ($search_query) {
    $articles_query = "SELECT * FROM saved_articles WHERE user_id = ? 
                       AND (title LIKE ? OR description LIKE ?) 
                       ORDER BY $order_by";
    $search_param = "%" . $search_query . "%";
    $stmt = mysqli_prepare($conn, $articles_query);
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $search_param, $search_param);
} else {
    $articles_query = "SELECT * FROM saved_articles WHERE user_id = ? ORDER BY $order_by";
    $stmt = mysqli_prepare($conn, $articles_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
}

mysqli_stmt_execute($stmt);
$articles_result = mysqli_stmt_get_result($stmt);

mysqli_stmt_close($stmt);




// Ensure user is logged in

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// Fetch user details
$query = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found.");
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $gender = $_POST["gender"];
    $birthday = $_POST["birthday"];

    // Profile picture upload
    if (!empty($_FILES["profile_picture"]["name"])) {
        $target_dir = "uploads/";

        // Ensure uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Generate unique filename
        $file_extension = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid("profile_", true) . "." . $file_extension;
        $target_file = $target_dir . $new_filename;

        // Move uploaded file
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        } else {
            echo "<script>alert('File upload failed. Please try again.');</script>";
            $profile_picture = $user["profile_picture"]; // Keep existing profile picture if upload fails
        }
    } else {
        $profile_picture = $user["profile_picture"];
    }

    // Update user details
    $update_query = "UPDATE users SET firstname=?, lastname=?, gender=?, birthday=?, profile_picture=? WHERE email=?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssss", $firstname, $lastname, $gender, $birthday, $profile_picture, $username);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile!');</script>";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Profile</h2>

    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" class="rounded-circle" width="150" height="150">
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="Male" <?php echo ($user['gender'] == "Male") ? "selected" : ""; ?>>Male</option>
                        <option value="Female" <?php echo ($user['gender'] == "Female") ? "selected" : ""; ?>>Female</option>
                        <option value="Other" <?php echo ($user['gender'] == "Other") ? "selected" : ""; ?>>Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="birthday" class="form-label">Birthday</label>
                    <input type="date" name="birthday" id="birthday" class="form-control" value="<?php echo htmlspecialchars($user['birthday']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>

            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>

<div class="container">
    <h2 class="mt-5">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <h3>Your Saved Articles</h3>

    <!-- Search and Sort Form -->
    <form method="GET" action="profile.php" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search articles..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
            <div class="col-md-6">
                <select name="sort_by" class="form-select" onchange="this.form.submit()">
                    <option value="date_saved_desc" <?php echo ($sort_by == "date_saved_desc") ? "selected" : ""; ?>>Recently Saved</option>
                    <option value="published_desc" <?php echo ($sort_by == "published_desc") ? "selected" : ""; ?>>Newest Published</option>
                    <option value="title_asc" <?php echo ($sort_by == "title_asc") ? "selected" : ""; ?>>Alphabetically (A-Z)</option>
                </select>
            </div>
        </div>
    </form>

    <?php if (mysqli_num_rows($articles_result) > 0): ?>
        <ul class="list-group">
            <?php while ($article = mysqli_fetch_assoc($articles_result)): ?>
                <li class="list-group-item">
                    <h4><?php echo htmlspecialchars($article["title"]); ?></h4>
                    <p><strong>Published on:</strong> <?php echo date("F j, Y, g:i a", strtotime($article["published_at"])); ?></p>
                    <p><?php echo htmlspecialchars($article["description"]); ?></p>
                    <a href="<?php echo htmlspecialchars($article["url"]); ?>" target="_blank" class="btn btn-primary">Read More</a>
                    
                    
                    <form method="POST" action="unsave_article.php" class="d-inline">
                        <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                        <button type="submit" class="btn btn-danger">Unsave</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
        
    <?php else: ?>
        <p>No saved articles found.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

</body>
</html>
