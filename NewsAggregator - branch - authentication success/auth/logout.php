<?php
session_start();

// Unset session variables
unset($_SESSION["username"]);
unset($_SESSION["password"]);

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
     
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('https://www.transparenttextures.com/patterns/stardust.png');
            background-size: cover;
        }

       
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logout-container {
            text-align: center;
            background-color: #1e1e1e;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 400px;
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid #64a64d; 
            animation: fadeIn 1s ease-out; 
        }

        h1 {
            color: #f0c68c; 
            margin-bottom: 25px;
            font-size: 1.8em;
            font-weight: bold;
        }

        p {
            font-size: 16px;
            color: #b0b0b0;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            text-decoration: none;
            background-color: #7a9d55;
            color: #121212;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #6b8e47; 
        }

        
        .register-link {
            margin-top: 20px;
            font-size: 14px;
        }

    </style>
</head>
<body>
    <div class="logout-container">
        <h1>You Have Logged Out</h1>
        <p>You will be redirected to the login page shortly.</p>
        <p>If you are not redirected, click the button below:</p>
        <a href="login.php">Go to Login</a>
    </div>

    <!-- Automatic Redirect -->
    <script>
        setTimeout(() => {
            window.location.href = "login.php";
        }, 3000); // Redirect after 3 seconds
    </script>
</body>
</html>
