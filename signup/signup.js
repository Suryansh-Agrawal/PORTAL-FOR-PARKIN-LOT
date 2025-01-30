document.getElementById('signup-form').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent form submission
  
    // Get the input values
    const orgName = document.getElementById('org-name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
  
    // Validate form fields
    if (orgName === "" || email === "" || phone === "" || password === "" || confirmPassword === "") {
      alert("Please fill in all fields.");
      return;
    }
  
    // Validate email format
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
      alert("Please enter a valid email address.");
      return;
    }
  
    // Validate phone number (simple validation for 10 digits)
    const phonePattern = /^\d{10}$/;
    if (!phonePattern.test(phone)) {
      alert("Please enter a valid phone number (10 digits).");
      return;
    }
  
    // Check if passwords match
    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      return;
    }
  
    // Password validation (optional: add minimum length or complexity checks)
    if (password.length < 6) {
      alert("Password must be at least 6 characters long.");
      return;
    }
  
    // Simulate successful signup (Normally, send this data to the server)
    alert("Signup successful! Redirecting to login page...");
    
    // Normally, you would redirect the user:
    // window.location.href = "../index.html"; // Redirect to login page
    document.getElementById('signup-form').addEventListener('submit', function (event) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm-password').value;
  
      if (password !== confirmPassword) {
          event.preventDefault();
          alert('Passwords do not match!');
      }
  });
  
  });
  