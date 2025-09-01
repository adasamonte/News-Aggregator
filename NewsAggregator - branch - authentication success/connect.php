<?php

$hostname     = "localhost"; 
$username     = "root";  
$password     = "";   
$databasename = "newsagg";  

// Create connection 
$conn = new mysqli($hostname, $username, $password, $databasename);

// Check connection 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8mb4 for better compatibility with emojis and special characters
$conn->set_charset("utf8mb4");

?>