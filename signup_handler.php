<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Return error response as JSON
        echo json_encode(["success" => false, "message" => "Email already registered. Please use a different email."]);
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashing the password
        $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Return success response as JSON
            echo json_encode(["success" => true, "message" => "Registration successful! You can now log in."]);
        } else {
            // Return error response as JSON
            echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
        }
    }

    $stmt->close();
}
?>
