<?php
require_once '../db/connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orgName = $_POST['org-name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password

    try {
        // Prepare the SQL query using `mysqli` syntax
        $stmt = $conn->prepare("INSERT INTO users (org_name, email, phone, password) VALUES (?, ?, ?, ?)");

        // Bind parameters to the query
        $stmt->bind_param("ssss", $orgName, $email, $phone, $password);

        // Execute the query
        $stmt->execute();

        // Redirect to login page after successful signup
        header('Location: ../index.php');
        exit();
    } catch (Exception $e) {
        // Check if the error is due to a duplicate email
        if ($conn->errno == 1062) {
            echo "<script>alert('Error: This email is already registered. Please log in or use a different email.');</script>";
            echo "<script>window.location.href = 'signup.html';</script>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
