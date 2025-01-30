<?php
// Include database connection
require_once('../db/connection.php');

// Initialize response array
$response = array();

// Check if vehicle number is provided
if (isset($_POST['vehicle-number'])) {
    $vehicle_number = mysqli_real_escape_string($conn, $_POST['vehicle-number']);

    // Query the database to check if the vehicle exists
    $sql = "SELECT * FROM vehicles WHERE vehicle_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $vehicle_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Vehicle found, generate the exit receipt
        $vehicle_data = $result->fetch_assoc();
        $exit_time = date("Y-m-d H:i:s");

        // Generate receipt data to return to the frontend
        $response['success'] = true;
        $response['vehicle_number'] = $vehicle_number;
        $response['exit_time'] = $exit_time;

        // Optional: Update the vehicle's exit time in the database
        // $update_sql = "UPDATE vehicles SET exit_time = ? WHERE vehicle_number = ?";
        // $stmt = $conn->prepare($update_sql);
        // $stmt->bind_param("ss", $exit_time, $vehicle_number);
        // $stmt->execute();
    } else {
        // Vehicle not found
        $response['success'] = false;
        $response['message'] = 'Vehicle not found. Please check the vehicle number.';
    }
} else {
    // If vehicle number is not provided
    $response['success'] = false;
    $response['message'] = 'No vehicle number provided.';
}

// Return response as JSON
echo json_encode($response);
?>
