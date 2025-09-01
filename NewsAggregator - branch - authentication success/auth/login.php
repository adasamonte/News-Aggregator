<?php
session_start();
require_once '../connect.php'; // Adjusted path to connect.php

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the user exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start session and set user data
            $_SESSION['user_id'] = $user['id']; // Assuming you have an 'id' field
            $_SESSION['username'] = $user['username']; // Store username if needed

            // Redirect to dashboard
            header("Location: ../dashboard.php");
            exit();
        } else {
            echo json_encode(["message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["message" => "User not found."]);
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Future Business Teachers' Organization</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
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
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email"><i class="fas fa-user me-2"></i>Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                        <a href="#" class="float-end text-decoration-none">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Sign In</button>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to handle slide-in animation and form submission
        function handleSlideInAndSubmit(form) {
            // Apply the slide-in animation to the login container
            document.querySelector('.login-container').style.animation = 'slideIn 0.5s ease forwards'; // Apply slide-in animation

            // Wait for the animation to finish before submitting the form
            setTimeout(() => {
                form.submit(); // Submit the form after the animation
            }, 500); // Match this duration with the animation duration
        }

        // Add event listener to the login form submission
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            handleSlideInAndSubmit(this); // Call the function to handle animation and submission
        });
    </script>
</body>
</html>
