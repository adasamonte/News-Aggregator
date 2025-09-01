<?php
session_start();
require_once "../connect.php"; // Adjust the path as necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the POST request
    $username = trim($_POST["username"]);
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $gender = trim($_POST["gender"]); // Assuming gender is sent as a string
    $birthday = isset($_POST["birthday"]) ? trim($_POST["birthday"]) : null; // Optional
    $profile_picture = isset($_POST["profile_picture"]) ? trim($_POST["profile_picture"]) : 'default.png'; // Default value

    // Validate input
    if (empty($username) || empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        echo json_encode(["error" => "Please fill in all fields."]);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert admin user into the database
    $insert_query = "INSERT INTO users (username, firstname, lastname, email, password, is_admin, gender, birthday, profile_picture) VALUES (?, ?, ?, ?, ?, 1, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssssiss", $username, $firstname, $lastname, $email, $hashed_password, $gender, $birthday, $profile_picture);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => "Admin account created successfully!"]);
    } else {
        echo json_encode(["error" => "Error creating admin account: " . $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}
?>
