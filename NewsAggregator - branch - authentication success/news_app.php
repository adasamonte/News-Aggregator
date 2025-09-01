<?php
$API_KEY = "accc69e25fa74c8380c2abf5d2e198de";
$BASE_URL = "https://newsapi.org/v2/everything";

// List of predefined random topics
$random_topics = ["Technology", "Health", "Finance", "Sports", "Entertainment", "Science", "Politics"];
$default_topic = $random_topics[array_rand($random_topics)]; // Pick a random topic

// Get user input for topic
$topic = isset($_GET["topic"]) ? trim($_GET["topic"]) : "";

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

$articles = fetch_news($topic);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Aggregator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        form { margin-bottom: 20px; }
        input, select { padding: 8px; width: 250px; margin-right: 10px; }
        button { padding: 8px 12px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .article { margin-bottom: 20px; padding: 10px; border-bottom: 1px solid #ccc; }
        .article h3 { margin: 5px 0; }
        .article p { margin: 5px 0; }
        .article a { color: #007bff; text-decoration: none; }
        .article a:hover { text-decoration: underline; }
        .date { font-size: 14px; color: #666; }
        nav { background-color: #333; padding: 10px; }
        nav a { color: white; padding: 10px 15px; text-decoration: none; }
        nav a:hover { background-color: #555; }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <a href="dashboard.php">Back to Dashboard</a>
    </nav>

    <h1>News Aggregator</h1>

    <!-- Search Form -->
    <form method="GET">
        <input type="text" name="topic" placeholder="Enter topic..." value="<?php echo htmlspecialchars($topic); ?>">
        <select name="sort_by">
            <option value="published_desc" <?php echo ($sort_by == "published_desc") ? "selected" : ""; ?>>Newest First</option>
            <option value="title_asc" <?php echo ($sort_by == "title_asc") ? "selected" : ""; ?>>Alphabetically (A-Z)</option>
        </select>
        <button type="submit">Search</button>
    </form>

    <h2>Results for: <?php echo htmlspecialchars($topic); ?></h2>

    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
        <div class="article">
            <h3><?php echo htmlspecialchars($article["title"]); ?></h3>
            <p class="date"><strong>Published on:</strong> 
                <?php echo date("F j, Y, g:i a", strtotime($article["publishedAt"])); ?>
            </p>
            <p><?php echo htmlspecialchars($article["description"]); ?></p>
            <a href="<?php echo htmlspecialchars($article["url"]); ?>" target="_blank">Read more</a>
        </div>
        <form method="POST" action="save_article.php">
            <input type="hidden" name="title" value="<?php echo htmlspecialchars($article['title']); ?>">
            <input type="hidden" name="url" value="<?php echo htmlspecialchars($article['url']); ?>">
            <input type="hidden" name="published_at" value="<?php echo htmlspecialchars($article['publishedAt']); ?>">
            <input type="hidden" name="description" value="<?php echo htmlspecialchars($article['description']); ?>">
            <input type="hidden" name="author" value="<?php echo htmlspecialchars($article['author'] ?? 'Unknown'); ?>">
            <button type="submit" class="btn btn-success">Save Article</button>
        </form>

        <?php endforeach; ?>
    <?php else: ?>
        <p>No articles found for "<?php echo htmlspecialchars($topic); ?>". Try another topic.</p>
    <?php endif; ?>
</body>
</html>
