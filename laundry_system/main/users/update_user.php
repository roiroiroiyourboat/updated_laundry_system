<?php
require_once('users_db.php');

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_role = $_POST['user_role'];

    error_log("Received data: user_id=$user_id, username=$username, first_name=$first_name, last_name=$last_name, user_role=$user_role");

    // Validate input
    if (empty($user_id) || empty($username) || empty($first_name) || empty($last_name) || empty($user_role)) {
        $response['error'] = 'All fields are required.';
    } else {
        // Fetch current last active time to determine the user status
        $last_active_query = "SELECT last_active FROM users WHERE user_id = ?";
        $stmt = $con->prepare($last_active_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $last_active = new DateTime($user['last_active']);
        $current_time = new DateTime();
        $interval = $current_time->diff($last_active);
        $user_status = ($interval->days < 30) ? 'Active' : 'Inactive';

        // Update
        $query = "UPDATE users SET username = ?, first_name = ?, last_name = ?, user_role = ?, user_status = ? WHERE user_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssssi", $username, $first_name, $last_name, $user_role, $user_status, $user_id);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = $stmt->error;
        }
    }
}

echo json_encode($response);
?>