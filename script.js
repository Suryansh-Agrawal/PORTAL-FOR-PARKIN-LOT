document.getElementById('login-form').addEventListener('submit', function (event) {
  event.preventDefault(); // Prevent form submission

  // Simulate login by prompting for a name
  const userName = prompt("Enter your name:");

  if (userName) {
    // Save user's name to localStorage
    localStorage.setItem('userName', userName);

    // Redirect to the Action Selection page
    window.location.href = "actions/action.html"; // Ensure this path is correct based on your folder structure
  } else {
    alert("Please enter a valid name.");
  }
});
