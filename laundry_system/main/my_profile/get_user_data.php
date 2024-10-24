<?php 
    header('Content-Type: application/json'); 
    session_start();

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'laundry_db');

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
    }

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        //SQL query
        $query = "SELECT first_name, last_name, username, password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        
        //check if prepare() succeeded
        if ($stmt === false) {
            echo json_encode(['error' => 'Failed to prepare the statement: ' . $conn->error]);
            exit();
        }

        // Bind parameters and execute
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute() === false) {
            echo json_encode(['error' => 'Failed to execute the statement: ' . $stmt->error]);
            exit();
        }

        // Get the result and fetch the user data
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if user data was found
        if ($user) {
            echo json_encode($user);  //return user data as JSON
        } else {
            echo json_encode(['error' => 'No user found for the given ID.']);
        }

        //clsoe stmt
        $stmt->close();
    } else {
        echo json_encode(['error' => 'User is not logged in.']);
    }

    $conn->close();
?>