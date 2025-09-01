<?php
   require_once '../connect.php'; // Adjust the path as necessary

   $email = 'kurtpogi38@gmail.com'; // Hardcoded email for testing
   $query = "SELECT * FROM users WHERE email = ?";
   $stmt = $conn->prepare($query);
   $stmt->bind_param("s", $email);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
       echo "User found!";
   } else {
       echo "User not found.";
   }

   $stmt->close();
   $conn->close();
   ?>