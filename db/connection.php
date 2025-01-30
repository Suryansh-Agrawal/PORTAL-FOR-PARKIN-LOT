<?php
$host = "localhost";         // Your database host
$username = "root";          // Your database username
$password = "";              // Your database password (usually blank for XAMPP)
$dbname = "parking_seva";    // Your database name

// Create a new connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    // Display a user-friendly message instead of exposing database details
    die("Database connection failed. Please try again later.");
}

// Set the charset to utf8mb4 for better Unicode compatibility
if (!$conn->set_charset("utf8mb4")) {
    die("Error setting character set: " . $conn->error);
}

// Connection is successful
?>
