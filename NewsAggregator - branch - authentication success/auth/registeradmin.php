<?php
include_once("../connect.php");

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $is_admin = 1;
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        echo "<p style='text-align: center; color: red; font-size: 1.2em;'>Email already exists! Try another.</p>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new admin
        $sql = "INSERT INTO users (username, is_admin, firstname, lastname, password, email) 
                VALUES ('$username', '$is_admin', '$firstname', '$lastname', '$hashedPassword', '$email')";
        if (mysqli_query($conn, $sql)) {
            echo "<p style='text-align: center; color: #bb86fc; font-size: 1.2em;'>Registration successful!</p>";
            header('Refresh: 1; URL = login.php');
        } else {
            echo "<p style='text-align: center; color: red; font-size: 1.2em;'>Error: " . mysqli_error($conn) . "</p>";
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">  
<head>  
  <meta charset="utf-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
  <title>Admin Registration Form</title>  
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

    .form-container {
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
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.8em;
        font-weight: bold;
    }

    label {
        font-size: 14px;
        color: #b0b0b0;
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"], input[type="password"], input[type="email"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #333333;
        border-radius: 8px;
        background-color: #2c2c2c;
        color: #e0e0e0;
        font-size: 1em;
    }

    input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus {
        outline: none;
        border-color: #f0c68c; 
    }

    input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #7a9d55; 
        color: #121212;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease-in-out;
    }

    input[type="submit"]:hover {
        background-color: #6b8e47; 
    }

    .back-to-login {
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
    }

    .back-to-login a {
        color: #bb86fc;
        text-decoration: none;
        font-size: 14px;
    }

    .back-to-login a:hover {
        text-decoration: underline;
    } 
  </style>  
</head>  
<body>    
    <div class="form-container">
        <h1>Admin Registration</h1>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="firstname">Firstname:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Lastname:</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <input type="submit" name="submit" value="Submit">
        </form>
        <div class="back-to-login">
            <p>Already have an account? <a href="login.php">Login Here</a></p>
        </div>
    </div>
</body>
</html>
