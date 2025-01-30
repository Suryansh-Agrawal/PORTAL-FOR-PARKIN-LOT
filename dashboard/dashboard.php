<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Include database connection
require_once('../db/connection.php');

// Initialize receipt variable
$receipt = null;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get data from the form
    $vehicle_number = mysqli_real_escape_string($conn, $_POST['vehicle_number']);
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    
    // Set parking amount based on vehicle type
    $amount = ($vehicle_type === 'two_wheeler') ? 10 : 20;
    
    // Insert vehicle details into database
    $sql = "INSERT INTO vehicles (vehicle_number, vehicle_type, phone_number) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $vehicle_number, $vehicle_type, $phone_number);
    
    if ($stmt->execute()) {
        // After successful insertion, generate the receipt
        $receipt = [
            'org_name' => htmlspecialchars($_SESSION['user_name']),
            'vehicle_number' => $vehicle_number,
            'time_of_entry' => date("Y-m-d H:i:s"),
            'phone_number' => $phone_number,
            'amount' => $amount
        ];
    } else {
        $error_message = "Error submitting vehicle details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Parking Seva Portal</title>
  <link rel="stylesheet" href="dashboard.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    .receipt-container {
      width: 50%;
      margin: 20px auto;
      padding: 20px;
      border: 2px solid #000;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      background-color: #f9f9f9;
      text-align: center;
    }

    .receipt-container h2 {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 15px;
    }

    .receipt-container p {
      font-size: 18px;
      margin: 5px 0;
    }

    .receipt-container p strong {
      font-weight: bold;
    }

    #camera-container {
      display: none;
      text-align: center;
    }

    #camera-container video {
      border: 1px solid #ddd;
      border-radius: 10px;
    }

    #capture-button {
      margin-top: 10px;
      padding: 10px 20px;
      font-size: 16px;
      background-color: #007BFF;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #capture-button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <!-- Welcome Banner -->
  <div class="banner">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
  </div>

  <!-- Form to collect vehicle number, phone number, and vehicle type -->
  <div class="dashboard-container">
    <h2>Provide your vehicle details</h2>
    <form id="vehicle-form" method="POST">
      <div class="input-group">
        <label for="vehicle_number">Vehicle Number:</label>
        <input type="text" id="vehicle_number" name="vehicle_number" required placeholder="Enter your vehicle number">
      </div>
      
      <div class="input-group">
        <label for="vehicle_type">Vehicle Type:</label>
        <select id="vehicle_type" name="vehicle_type" required>
          <option value="">Select Vehicle Type</option>
          <option value="two_wheeler">Two Wheeler</option>
          <option value="four_wheeler">Four Wheeler</option>
        </select>
      </div>

      <div class="input-group">
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required placeholder="Enter phone number">
      </div>

      <button type="submit">Submit</button>
    </form>
    <button type="button" id="camera-icon">
        <i class="fas fa-camera"></i> Detect Vehicle Number
    </button>
  </div>

  <!-- Camera Feed -->
  <div id="camera-container">
    <video id="video" width="320" height="240" autoplay></video>
    <canvas id="canvas" style="display: none;"></canvas>
    <button id="capture-button" disabled>Capture</button>
  </div>

  <?php if ($receipt): ?>
  <!-- Receipt Display Section in a Box -->
  <div class="receipt-container">
    <h2>Receipt</h2>
    <p><strong>Organization:</strong> <?php echo $receipt['org_name']; ?></p>
    <p><strong>Vehicle Number:</strong> <?php echo $receipt['vehicle_number']; ?></p>
    <p><strong>Time of Entry:</strong> <?php echo $receipt['time_of_entry']; ?></p>
    <p><strong>Phone Number:</strong> <?php echo $receipt['phone_number']; ?></p>
    <p><strong>Amount:</strong> ₹<?php echo $receipt['amount']; ?></p>
  </div>
  <?php endif; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/tesseract.js/2.1.3/tesseract.min.js"></script>
  <script>
    document.getElementById("camera-icon").addEventListener("click", () => {
      const cameraContainer = document.getElementById("camera-container");
      const video = document.getElementById("video");
      const captureButton = document.getElementById("capture-button");

      // Show the camera container
      cameraContainer.style.display = "block";
      
      // Get access to the camera
      navigator.mediaDevices.getUserMedia({ video: true })
        .then((stream) => {
          video.srcObject = stream;
          video.play();
          captureButton.disabled = false;

          // Capture frame on button click
          captureButton.addEventListener("click", () => processCapturedFrame(video, stream));
        })
        .catch((err) => {
          alert("Error accessing camera: " + err.message);
        });
    });

    function processCapturedFrame(video, stream) {
      const canvas = document.getElementById("canvas");
      const ctx = canvas.getContext("2d");
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;

      // Capture the current frame from the video feed
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      Tesseract.recognize(canvas, 'eng', { logger: (m) => console.log(m) })
        .then(({ data: { text } }) => {
          const detectedText = text.trim();
          if (detectedText) {
            stream.getTracks().forEach(track => track.stop());
            document.getElementById("camera-container").style.display = "none";
            document.getElementById("vehicle_number").value = detectedText;
            alert("Detected text: " + detectedText);
          } else {
            alert("No text detected. Please try again.");
          }
        })
        .catch((err) => {
          console.error("Error during OCR:", err);
          alert("Error during OCR. Please try again.");
        });
    }
  </script>
</body>
</html>
