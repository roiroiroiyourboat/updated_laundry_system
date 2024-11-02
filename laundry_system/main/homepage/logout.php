<?php
session_start();

if (session_destroy()) {
    header("Location: /laundry_system/main/homepage/homepage.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'laundry_db');

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => "Connection error: " . $conn->connect_error]));
}

?>