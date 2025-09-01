<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - Future Business Teachers' Organization</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/landing.css"> <!-- Link to the external CSS file -->
</head>
<body>

    <!-- Invisible Header -->
    <div class="header">
        <div class="nav-container"> <!-- Container for the navigation links -->
            <a href="news.php" class="nav-link">News</a>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="about.php" class="nav-link">About</a>
        </div>
    </div>

    <!-- Flex Container for Logo and Content -->
    <div class="container">
        <div class="logo">
            <img src="uploads/assets/logo.png" alt="Logo" class="logo-img">
        </div>
        <div class="description-container">
            <p class="description">A personalized news experience for users.<br>Filters, preferences, engagement.</p>
            <a href="auth/login.php" class="btn btn-outline-light">Get Started</a>
        </div>
    </div>

    <footer class="footer">
        <p>
            Powered by <img src="uploads/assets/mapua_Icowhite.png" alt="Mapua" class="footer-logo"> Mapúa University and 
            <img src="uploads/assets/FBTO_Icowhite.png" alt="Future Business Teachers' Organization" class="footer-logo"> Future Business Teachers' Organization.
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to handle slide-out animation and redirection
        function handleSlideOut(targetUrl) {
            // Apply the slide-out animation to the body or container
            document.body.style.animation = 'slideOut 0.5s ease forwards'; // Apply slide-out animation

            // Wait for the animation to finish before redirecting
            setTimeout(() => {
                window.location.href = targetUrl; // Redirect to the new page
            }, 500); // Match this duration with the animation duration
        }

        // Add event listeners to navigation links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior
                const targetUrl = this.href; // Get the target URL
                handleSlideOut(targetUrl); // Call the slide-out function
            });
        });

        // Add event listener to the "Get Started" button
        document.querySelector('.description-container .btn').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default button behavior
            const targetUrl = this.href; // Get the target URL
            handleSlideOut(targetUrl); // Call the slide-out function
        });

        window.onload = function() {
            const container = document.querySelector('.container');
            container.style.animation = 'none'; // Reset the animation
            // Trigger reflow to restart the animation
            container.offsetHeight; // This forces a reflow
            container.style.animation = 'slideIn 1s ease forwards'; // Trigger slide-in animation on load
        };
    </script>
</body>
</html>