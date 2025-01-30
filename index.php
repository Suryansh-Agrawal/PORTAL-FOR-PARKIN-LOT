<?php
session_start();
require_once('db/connection.php'); // Ensure the correct path to connection.php

// Initialize error message variable
$error_message = '';

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
            
            // Redirect to the action.html page after successful login
            header('Location: actions/action.html'); // Updated path to action.html
            exit();
        } else {
            // Password does not match
            $error_message = "Invalid password.";
        }
    } else {
        // User not found
        $error_message = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page - Parking Seva Portal</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Banner Section -->
  <div class="banner">
    <h1>Parking Seva Portal</h1>
  </div>

  <div class="login-container">
    <h2>Login</h2>

    <!-- Display error message if exists -->
    <div class="error-message">
      <?php
      if ($error_message) {
          echo "<p style='color: red;'>$error_message</p>";
      }
      ?>
    </div>

    <!-- Form for login -->
    <form id="login-form" method="POST">
      <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required placeholder="Enter your email">
      </div>
      <div class="input-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required placeholder="Enter your password">
      </div>
      <button type="submit">Login</button>
    </form>

    <p class="signup-link">
      Don't have an account? <a href="signup/signup.html">Sign up here</a>
    </p>
  </div>
</body>
</html>
