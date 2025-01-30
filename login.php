<?php
session_start();
require_once('db/connection.php'); // Ensure the correct path to connection.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // No need to escape the password, we will hash/compare it directly.

    // Prepare SQL query to fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" means string type for email
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists and verify password
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password using password_verify() if it's hashed
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session and store user info
            $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the primary key in your table
            $_SESSION['user_name'] = $user['org_name']; // Assuming 'org_name' is stored in the table
            
            // Redirect to the dashboard
            header('Location: dashboard/dashboard.html');
            exit();
        } else {
            // Password does not match
            $_SESSION['error'] = "Invalid password.";
            header('Location: index.php');
            exit();
        }
    } else {
        // User not found
        $_SESSION['error'] = "No account found with this email.";
        header('Location: index.php');
        exit();
    }
}
?>
