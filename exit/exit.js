// Handle form submission for exit
document.getElementById('exit-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent form from submitting normally

  const vehicleNumber = document.getElementById('vehicle-number').value;

  // Create a FormData object to send the vehicle number to the server
  const formData = new FormData();
  formData.append('vehicle-number', vehicleNumber);

  // Send the vehicle number to a PHP script to check if it's present in the database
  fetch('exit.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Display receipt if vehicle is found
      document.getElementById('receipt').style.display = 'block';
      document.getElementById('receipt-vehicle-number').textContent = data.vehicle_number;
      document.getElementById('receipt-exit-time').textContent = data.exit_time;

      // Hide error message if receipt is shown
      document.getElementById('error-message').style.display = 'none';
    } else {
      // Show error message if vehicle is not found
      document.getElementById('error-message').style.display = 'block';
      document.getElementById('receipt').style.display = 'none'; // Hide receipt section
    }
  })
  .catch(error => console.error('Error:', error));
});
