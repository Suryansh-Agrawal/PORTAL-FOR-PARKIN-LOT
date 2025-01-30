// Handle Entry Button Click
document.getElementById('entry-button').addEventListener('click', function () {
    // Correct relative path to dashboard.php
    window.location.href = "dashboard/dashboard.php"; // Ensure the single slash between folder names
});

// Handle Exit Button Click
document.getElementById('exit-button').addEventListener('click', function () {
    // Correct relative path to exit.html
    window.location.href = "/exit/exit.html"; // Ensure the single slash between folder names
});
