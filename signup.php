<?php
include 'db.php'; // Ensure you keep the database connection for backend processing.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Link to external CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>
<body>
    <div class="container">
        <h2>Signup</h2>
        <form id="signupForm" method="post">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Sign Up</button>
        </form>
        <div id="responseMessage" class="responseMessage"></div> <!-- Placeholder for success/error message -->
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <script>
        $(document).ready(function() {
            $('#signupForm').submit(function(event) {
                event.preventDefault(); // Prevent form submission

                var formData = $(this).serialize(); // Collect form data

                $.ajax({
                    url: 'signup_handler.php', // PHP script that will process the signup
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        var result = JSON.parse(response); // Parse JSON response from PHP
                        var message = result.message;
                        var messageClass = result.success ? 'success' : 'error';

                        // Display message with appropriate styling
                        $('#responseMessage').text(message).removeClass('error success').addClass(messageClass);

                        // If registration was successful, clear the form fields
                        if (result.success) {
                            clearFormFields(); // Call function to clear the form fields
                        }
                    },
                    error: function() {
                        $('#responseMessage').text('An error occurred. Please try again.').removeClass('success').addClass('error');
                    }
                });
            });

            // Function to clear the form fields
            function clearFormFields() {
                $('#fullname').val('');   // Clear Full Name input
                $('#email').val('');      // Clear Email input
                $('#password').val('');   // Clear Password input
            }
        });
    </script>
</body>
</html>
