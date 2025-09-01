<?php
require_once '../connect.php'; // Adjusted path to connect.php

// Fetch all users
$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        $plainPassword = $row['password'];

        // Hash the password
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $updateSql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $hashedPassword, $userId);
        $stmt->execute();
    }
    echo "Passwords have been hashed and updated successfully.";
} else {
    echo "No users found.";
}

$conn->close();
?>
