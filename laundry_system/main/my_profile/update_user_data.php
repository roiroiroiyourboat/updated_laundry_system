<?php
session_start();

//check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //get JSON input field
    $data = json_decode(file_get_contents('php://input'), true);

    //extract form data
    $firstName = $data['firstName'];
    $lastName = $data['lastName'];
    $username = $data['username'];
    $currentPass = isset($data['currentPass']) ? $data['currentPass'] : null;
    $newPass = isset($data['newPass']) ? $data['newPass'] : null;

    //user ID from session
    $userId = $_SESSION['user_id'];

    $mysqli = new mysqli("localhost", "root", "", "laundry_db");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    //if the password is provided, validate and update it
    if ($currentPass && $newPass) {
        // the current password hash from the database
        $stmt = $mysqli->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($storedPasswordHash);
        $stmt->fetch();
        $stmt->close();

        //check the current password
        if (!password_verify($currentPass, $storedPasswordHash)) {
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
            exit();
        }

        //hash the new password
        $newPassHash = password_hash($newPass, PASSWORD_BCRYPT);

        //update user profile
        $updateStmt = $mysqli->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, username = ?, password = ? 
            WHERE user_id = ?
        ");
        $updateStmt->bind_param("ssssi", $firstName, $lastName, $username, $newPassHash, $userId);
    } else {
        //only update the name and username
        $updateStmt = $mysqli->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, username = ? 
            WHERE user_id = ?
        ");
        $updateStmt->bind_param("sssi", $firstName, $lastName, $username, $userId);
    }

    //update query
    if ($updateStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
    }

    $updateStmt->close();
    $mysqli->close();
}
?>
