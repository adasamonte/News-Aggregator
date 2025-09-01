<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'News Aggregator'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/universal.css">
    <?php if (isset($additional_css) && is_array($additional_css)): ?>
        <?php foreach ($additional_css as $css_file): ?>
            <link rel="stylesheet" href="<?php echo $css_file; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Universal Header -->
    <div class="universal-header">
        <div class="header-container">
            <img src="uploads/assets/logo-ico.png" alt="Logo" class="header-logo">
            <div class="nav-links">
                <a href="news_app.php" class="nav-link">News</a>
                <a href="profile.php" class="nav-link">Dashboard</a>
                <a href="about.php" class="nav-link">About</a>
                <a href="landing.php" class="nav-link" id="exit-icon" title="Exit to Landing Page">
                    <i class="fas fa-door-open"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Content Container -->
    <div class="main-content"> 