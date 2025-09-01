<?php
session_start();
require_once '../connect.php'; // Adjusted path to connect.php

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]); // Get email from form submission
    $password = $_POST["password"];
    $rememberMe = isset($_POST["remember"]); // Check if "Remember Me" is checked

    error_log("Checking for user with email: " . $email); // Log the email being checked

    // Check if the user exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        $error = "Database error. Please try again later.";
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            error_log("User found: " . print_r($user, true)); // Log user details
            
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Start session and set user data
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email']; // Store email in session
                $_SESSION['is_admin'] = $user['is_admin']; // Store admin status
                
                error_log("Login successful. Admin status: " . $user['is_admin']);

                // Set a cookie if "Remember Me" is checked
                if ($rememberMe) {
                    setcookie("user_email", $email, time() + (86400 * 30), "/"); // 30 days
                } else {
                    // Clear the cookie if not checked
                    if (isset($_COOKIE["user_email"])) {
                        setcookie("user_email", "", time() - 3600, "/"); // Expire the cookie
                    }
                }

                // Check if the user is an admin
                if ($user['is_admin'] == 1) {
                    error_log("Redirecting to admin dashboard");
                    // Redirect to admin dashboard
                    header("Location: ../admin/dashboard.php");
                    exit();
                } else {
                    error_log("Redirecting to user profile");
                    // Redirect to user profile page
                    header("Location: ../profile.php");
                    exit();
                }
            } else {
                error_log("Invalid password for email: " . $email); // Log invalid password attempt
                $error = "Invalid password";
            }
        } else {
            error_log("User not found for email: " . $email); // Log if user is not found
            $error = "User not found";
        }

        $stmt->close();
    }
}

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
} else {
    error_log("Connected successfully to the database.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | News For You</title>
    <link rel="icon" href="../uploads/assets/logo-ico-tab.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/login.css">
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
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="back-arrow" onclick="window.history.back();">
                <i class="fas fa-arrow-left"></i>
            </div>
            <div class="login-form">
                <div class="text-center mb-4">
                    <h2 class="mb-3">Welcome Back!</h2>
                    <p class="text-muted">Future Business Teachers' Organization</p>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : ''; ?>">
                        <label for="email"><i class="fas fa-user me-2"></i>Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Sign In</button>

                    <div class="divider">
                        <span>or</span>
                    </div>

                    <div class="social-login">
                        <p class="text-muted mb-2">Don't have an account?</p>
                        <a href="register.php" class="btn btn-outline-secondary w-100">Create Account</a>
                    </div>
                </form>
            </div>
            <div class="login-image"></div>
        </div>
    </div>

    <footer class="footer">
        <p>
            Powered by <img src="../uploads/assets/mapua_Icowhite.png" alt="Mapua" class="footer-logo"> Mapúa University and 
            <img src="../uploads/assets/FBTO_Icowhite.png" alt="Future Business Teachers' Organization" class="footer-logo"> Future Business Teachers' Organization.
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
