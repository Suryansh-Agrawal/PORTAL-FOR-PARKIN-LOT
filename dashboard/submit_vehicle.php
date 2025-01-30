<?php
require_once('../db/connection.php'); // Ensure connection.php is correctly included

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_number = trim($_POST['vehicle_number']);
    $phone_number = trim($_POST['phone_number']);
    $vehicle_type = trim($_POST['vehicle_type']);
    $time_of_entry = date('Y-m-d H:i:s');

    if (!empty($vehicle_number) && !empty($phone_number) && !empty($vehicle_type)) {
        $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_number, phone_number, vehicle_type, time_of_entry) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $vehicle_number, $phone_number, $vehicle_type, $time_of_entry);

        if ($stmt->execute()) {
            $receipt_id = $stmt->insert_id; // Get the auto-increment ID
            header("Location: generate_receipt.php?receipt_id=$receipt_id");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill out all fields.";
    }
}

$conn->close();
?>
