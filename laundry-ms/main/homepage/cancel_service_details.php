<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'db_laundry');

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Retrieve POST data
if (!isset($_POST['service_request_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Service request ID is required']);
    exit;
}

$serviceRequestId = $_POST['service_request_id'];

// Cancel service request from database
$sqlCancelServiceRequest = "UPDATE tbl_service_request SET status = 'cancelled' WHERE request_id  =?";
$stmtCancelServiceRequest = $conn->prepare($sqlCancelServiceRequest);
$stmtCancelServiceRequest->bind_param('i', $serviceRequestId);
$stmtCancelServiceRequest->execute();

if ($stmtCancelServiceRequest->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Service request cancelled successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to cancel service request']);
}

// Check for errors
if ($stmtCancelServiceRequest->error) {
    echo json_encode(['status' => 'error', 'message' => 'Error executing query: ' . $stmtCancelServiceRequest->error]);
}

$stmtCancelServiceRequest->close();
$conn->close();
?>