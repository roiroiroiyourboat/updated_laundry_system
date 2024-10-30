<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$db_name = "laundry_db";

$conn = new mysqli($servername, $username, $password, $db_name);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => "Connection error: " . $conn->connect_error]));
}  

//process form validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $user_role = trim($_POST['user_role']);
    $password = trim($_POST['password']);
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    if (empty($username) || empty($fname) || empty($lname) || empty($password || empty($question) || empty($answer))) {
        echo json_encode(['status' => 'error', 'message' => "Error: All fields are required."]);
    } else {
        // Check if username already exists
        $check_username_sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_username_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => "Error: Username already exists!"]);
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $hashed_answer = password_hash($answer, PASSWORD_DEFAULT);

            // User status
            $user_status = 'Active';

            $insert_user_sql = "INSERT INTO users (username, first_name, last_name, user_role, password, last_active, user_status, date_created, question, answer) VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW(),?,?)";
            $stmt = $conn->prepare($insert_user_sql);
            $stmt->bind_param("ssssssss", $username, $fname, $lname, $user_role, $hashed_password, $user_status, $question, $hashed_answer);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => "User added successfully!"]);
            } else {
                echo json_encode(['status' => 'error', 'message' => "Error: Could not save the user information."]);
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>
