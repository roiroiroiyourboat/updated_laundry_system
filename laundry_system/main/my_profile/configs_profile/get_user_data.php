<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'laundry_db';

//connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//retrieve user data
$user_id = $_SESSION['user_id'];
$query = "SELECT username, first_name, last_name, password, profile_picture FROM users WHERE id = '$user_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "No user data found";
}

$conn->close();

// Output user data as JSON
echo json_encode($user_data);
?>