<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laundry_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_name = $_POST['username'];
$user_password = $_POST['password'];

// Check if the username or password is empty
if (empty($user_name) || empty($user_password)) {
    echo "Error: Username and Password are required.";
} else {
    // Hash the password
    $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

    // Prepare and bind to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $user_name, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>