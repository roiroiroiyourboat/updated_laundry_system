<?php
session_start();
header('Content-Type: application/json');

try {
    $conn = new mysqli('localhost', 'root', '', 'db_laundry');

    if ($conn->connect_error) {
        throw new Exception('Connection Failed: ' . $conn->connect_error);
    }

    // Get the latest customer ID from tbl_customer
    $result = $conn->query("SELECT MAX(customer_id) as customer_id FROM tbl_customer");
    $row = $result->fetch_assoc();
    $customer_id = $row['customer_id'] + 1;

    $conn->close();

    echo json_encode(['status' => 'success', 'customer_id' => $customer_id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
