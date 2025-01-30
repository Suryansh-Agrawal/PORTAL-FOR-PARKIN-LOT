// Display the user's name in the welcome banner
document.getElementById('user-name').textContent = localStorage.getItem('userName') || "Guest";

// Handle form submission for vehicle details
document.getElementById('vehicle-form').addEventListener('submit', function (event) {
  event.preventDefault(); // Prevent default form submission

  const vehicleNumber = document.getElementById('vehicle-number').value;
  const vehicleType = document.getElementById('vehicle-type').value;
  const phone = document.getElementById('phone').value;

  // Validate fields
  if (vehicleNumber === "" || phone === "" || vehicleType === "") {
    alert("Please fill in all fields.");
    return;
  }

  // Determine price based on vehicle type
  const price = vehicleType === "two-wheeler" ? 10 : 20;

  // Get the current time
  const entryTime = new Date().toLocaleString();

  // Generate receipt content
  const receiptContent = `
    <div class="receipt">
      <h1>Parking Seva Portal</h1>
      <p><strong>Time of Entry:</strong> ${entryTime}</p>
      <p><strong>Vehicle Number:</strong> ${vehicleNumber}</p>
      <p><strong>Phone Number:</strong> ${phone}</p>
      <p><strong>Price:</strong> ₹${price}</p>
    </div>
  `;

  // Insert receipt at the bottom of the page
  const receiptContainer = document.createElement('div');
  receiptContainer.innerHTML = receiptContent;
  document.body.appendChild(receiptContainer);

  // Optionally, clear the form after submission
  document.getElementById('vehicle-form').reset();
});

// Handle click event for the camera icon
document.getElementById('camera-icon').addEventListener('click', function () {
  const cameraContainer = document.getElementById('camera-container');
  const video = document.getElementById('video');
  const captureButton = document.getElementById('capture-button');

  // Show the camera container
  cameraContainer.style.display = "block";

  // Access the camera
  navigator.mediaDevices.getUserMedia({ video: true })
    .then((stream) => {
      video.srcObject = stream;
      video.play();

      // Enable the capture button after the video starts
      captureButton.disabled = false;

      // Handle the capture button click
      captureButton.addEventListener('click', function () {
        processCapturedFrame(video, stream);
      });
    })
    .catch((err) => {
      alert("Error accessing camera: " + err.message);
    });
});

// Function to process the captured frame and apply OCR
function processCapturedFrame(video, stream) {
  const canvas = document.getElementById('canvas');
  const ctx = canvas.getContext('2d');
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;

  // Capture the current frame from the video feed
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

  // Perform OCR using Tesseract.js
  Tesseract.recognize(
    canvas,
    'eng',
    { logger: (m) => console.log(m) }
  ).then(({ data: { text } }) => {
    const vehicleNumber = text.trim();
    const regex = /^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,2}[0-9]{1,4}$/i; // Regex to validate vehicle number formats

    if (regex.test(vehicleNumber)) {
      // Stop the camera stream
      stream.getTracks().forEach(track => track.stop());

      // Hide the camera container
      document.getElementById('camera-container').style.display = "none";

      // Set the detected vehicle number into the input field
      document.getElementById('vehicle-number').value = vehicleNumber;

      // Notify the user
      alert("Vehicle number detected: " + vehicleNumber);
    } else {
      alert("No valid vehicle number detected. Please try again.");
    }
  }).catch(err => {
    console.error("Error during OCR:", err);
    alert("Error during OCR. Please try again.");
  });
}
