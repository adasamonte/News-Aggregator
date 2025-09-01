<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News For You</title>
    <link rel="icon" href="uploads/assets/logo-ico-tab.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/landing.css">
    <link rel="stylesheet" href="style/universal.css">
    <style>
        @keyframes slideInFromBottom {
            0% {
                transform: translateY(100%);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .slide-in {
            animation: slideInFromBottom 0.5s ease forwards;
        }

        @keyframes slideOutToTop {
            0% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(-100%); /* Move out of view to the top */
                opacity: 0;
            }
        }

        .slide-out-top {
            animation: slideOutToTop 0.5s ease forwards; /* Slide out to top animation */
        }
    </style>
</head>
<body>

    <!-- Invisible Header -->
    <div class="header">
        <div class="nav-container"> <!-- Container for the navigation links -->
            <a href="news.php" class="nav-link">News</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../profile.php" class="nav-link">Profile</a>
            <?php else: ?>
                <a href="auth/login.php" class="nav-link">Dashboard</a>
            <?php endif; ?>
            <a href="about.php" class="nav-link">About</a>
        </div>
    </div>

    <!-- Flex Container for Logo and Content -->
    <div class="container">
        <div class="logo">
            <img src="uploads/assets/logo.png" alt="Logo" class="logo-img">
        </div>
        <div class="description-container">
            <p class="description">A personalized news experience for users.<br>filters, preferences, engagement.</p>
            <a href="auth/login.php" class="btn">Get Started</a>
        </div>
    </div>

    <footer class="footer">
        <p>
            Powered by <img src="uploads/assets/mapua_Icowhite.png" alt="Mapua" class="footer-logo"> Mapúa University and 
            <img src="uploads/assets/FBTO_Icowhite.png" alt="Future Business Teachers' Organization" class="footer-logo"> Future Business Teachers' Organization.
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="path/to/your/js/landing.js"></script>
</body>
</html>