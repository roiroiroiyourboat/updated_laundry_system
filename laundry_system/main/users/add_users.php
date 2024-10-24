<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$db_name = "laundry_db";

$conn = new mysqli($servername, $username, $password, $db_name);

if ($conn -> connect_error) {
    die("Connection error: " . $conn->connect_error);
}  

//process form validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $user_role = trim($_POST['user_role']);
    $password = trim($_POST['password']);

    //hashing the password using bcrypt
    //$hashed_password = password_hash($password, PASSWORD_BCRYPT);

    if (empty($username) || empty($fname) || empty($lname) | empty($password)) {
        $error_message = "Error: All fields are required to be filled.";
    } else {
        // Check if username already exists
        $check_username_sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_username_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Error: Username already exist!";
        } else {
            
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // to determine user status
            $user_status = 'Active';

            $insert_user_sql = "INSERT INTO users (username, first_name, last_name, user_role, password, last_active, user_status, date_created) VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW())";
            $stmt = $conn->prepare($insert_user_sql);
            $stmt->bind_param("ssssss", $username, $fname, $lname, $user_role, $hashed_password, $user_status);
            
            if ($stmt->execute()) {
                header("Location: users.php?success=1");
            } else {
                $error_message = "Error: Could not save the user information.";
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>