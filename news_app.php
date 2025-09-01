<?php
// Start the session and include necessary files
session_start();
include("connect.php");

// Set page title and additional CSS files
$page_title = "News | News For You";
$additional_css = ["style/news_app.css", "style/universal.css"]; // Add any specific CSS for news app

// Include the universal header
include("includes/header.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php"); // Redirect to login page if not logged in
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check for blacklisted keywords for this user
$blacklisted_keywords = [];
$blacklist_query = "SELECT keyword FROM blacklist WHERE user_id = ?";
$stmt = $conn->prepare($blacklist_query);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $blacklisted_keywords[] = strtolower($row['keyword']);
    }
    $stmt->close();
}

$API_KEY = "accc69e25fa74c8380c2abf5d2e198de";
$BASE_URL = "https://newsapi.org/v2/everything";

// List of predefined random topics
$random_topics = ["Technology", "Health", "Finance", "Sports", "Entertainment", "Science", "Politics"];
$default_topic = $random_topics[array_rand($random_topics)]; // Pick a random topic

// Get user input for topic
$topic = isset($_GET["topic"]) ? trim($_GET["topic"]) : "";

// Check if the search topic contains any blacklisted keywords
$search_blocked = false;
$blocked_keyword = "";
if (!empty($topic)) {
    foreach ($blacklisted_keywords as $keyword) {
        if (stripos(strtolower($topic), $keyword) !== false) {
            $search_blocked = true;
            $blocked_keyword = $keyword;
            break;
        }
    }
}

// Update page title based on search
if (!empty($topic)) {
    echo "<script>document.title = '" . htmlspecialchars($topic) . " | News For You';</script>";
}

// If no topic is set, use a random default
if (empty($topic)) {
    $topic = $default_topic;
}

// Get sorting preference
$sort_by = isset($_GET["sort_by"]) ? $_GET["sort_by"] : "published_desc"; // Default: Newest first

// Function to fetch news
function fetch_news($topic, $page_size = 30) {
    global $API_KEY, $BASE_URL;
    
    if (empty($topic)) {
        return [];
    }

    $params = http_build_query([
        "q" => $topic,
        "apiKey" => $API_KEY,
        "pageSize" => $page_size,
        "sortBy" => "relevancy",
        "language" => "en"
    ]);

    $url = "$BASE_URL?$params";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: MyNewsAggregator/1.0"]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $data = json_decode($response, true);
        return $data["articles"] ?? [];
    } else {
        echo "<p>Error fetching news. HTTP Code: $http_code</p>";
        return [];
    }
}

// Fetch articles only if search is not blocked
$articles = [];
if (!$search_blocked) {
    $articles = fetch_news($topic);
}

// Sorting logic
if ($sort_by === "title_asc") {
    usort($articles, function ($a, $b) {
        return strcmp($a["title"], $b["title"]);
    });
} elseif ($sort_by === "published_desc") {
    usort($articles, function ($a, $b) {
        return strtotime($b["publishedAt"]) - strtotime($a["publishedAt"]);
    });
}

// Add favicon link in the head section
echo '<link rel="icon" type="image/png" href="uploads/assets/logo-ico-tab.png">';
?>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <img src="uploads/assets/news.png" alt="News" class="hero-logo">
    </div>
</div>

<!-- Main Container -->
<div class="main-container">
    <img src="uploads/assets/news.png" alt="News" class="main-logo">
    <!-- Search Section -->
    <div class="search-section">
        <form method="GET" class="search-bar" id="searchForm">
            <input 
                type="text" 
                name="topic" 
                placeholder="Search news..." 
                value="<?php echo htmlspecialchars($topic); ?>"
                id="searchInput"
            >
            <select name="sort_by" class="form-select">
                <option value="published_desc" <?php echo ($sort_by == "published_desc") ? "selected" : ""; ?>>
                    Newest First
                </option>
                <option value="title_asc" <?php echo ($sort_by == "title_asc") ? "selected" : ""; ?>>
                    Alphabetically (A-Z)
                </option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Add JavaScript for dynamic title -->
    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                document.title = searchTerm + ' | News For You';
            } else {
                document.title = 'News | News For You';
            }
        });
    </script>

    <!-- Results Section -->
    <div class="articles-container">
        <?php if ($search_blocked): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Your search contains a blacklisted keyword. Please try a different search term.
            </div>
        <?php elseif (!empty($topic) || isset($_GET['category'])): ?>
            <h2>Results for: <?php echo htmlspecialchars($topic ?: $_GET['category']); ?></h2>
        <?php endif; ?>
        
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <h3><?php echo htmlspecialchars($article["title"]); ?></h3>
                    <p class="date">
                        <strong>Published on:</strong> 
                        <?php echo date("F j, Y, g:i a", strtotime($article["publishedAt"])); ?>
                    </p>
                    <p><?php echo htmlspecialchars($article["description"]); ?></p>
                    <div class="article-actions">
                        <a href="<?php echo htmlspecialchars($article["url"]); ?>" 
                           target="_blank" 
                           class="btn btn-primary">
                            Read More
                        </a>
                        <form method="POST" action="save_article.php" style="display: inline;">
                            <input type="hidden" name="title" 
                                value="<?php echo htmlspecialchars($article['title']); ?>">
                            <input type="hidden" name="url" 
                                value="<?php echo htmlspecialchars($article['url']); ?>">
                            <input type="hidden" name="published_at" 
                                value="<?php echo htmlspecialchars($article['publishedAt']); ?>">
                            <input type="hidden" name="description" 
                                value="<?php echo htmlspecialchars($article['description']); ?>">
                            <input type="hidden" name="author" 
                                value="<?php echo htmlspecialchars($article['author'] ?? 'Unknown'); ?>">
                            <button type="submit" class="btn btn-success">Save Article</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif (!$search_blocked): ?>
            <p class="no-results">No articles found. Try different search terms or category.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Include the universal footer -->
<?php include("includes/footer.php"); ?>
