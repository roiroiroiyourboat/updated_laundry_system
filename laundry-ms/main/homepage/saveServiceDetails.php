<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $customerId = $_POST['customer_id'] ?? null;
    $serviceId = $_POST['serviceId']?? null;
    $serviceOptionName = $_POST['service_option']?? null;
    $address = $_POST['address'] ?? null;
    $pickupDate = date('Y-m-d', strtotime($_POST['pickup_date'] ?? null));
    $totalAmount = $_POST['total_amount'] ?? null;
    $deliveryFee = $_POST['delivery_fee'] ?? null;
    $amountTendered = $_POST['amount_tendered'] ?? null;
    $change = $_POST['change'] ?? null;

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'db_laundry');

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit;
    }

    // Check if customer_id exists
    $sqlCustomer = "SELECT customer_id FROM tbl_customer WHERE customer_id = ?";
    $stmtCustomer = $conn->prepare($sqlCustomer);
    $stmtCustomer->bind_param('i', $customerId);
    $stmtCustomer->execute();
    $stmtCustomer->store_result();

    if ($stmtCustomer->num_rows > 0) {
        $stmtCustomer->close();
         //Update customer address in tbl_customer
        $sqlUpdateCustomer = "UPDATE tbl_customer SET address =? WHERE customer_id =?";
        $stmtUpdateCustomer = $conn->prepare($sqlUpdateCustomer);
        $stmtUpdateCustomer->bind_param('si', $address, $customerId);
        $stmtUpdateCustomer->execute();
        $stmtUpdateCustomer->close();

        // Get the existing request_id for the customer
        $sqlRequest = "SELECT request_id FROM tbl_service_request WHERE customer_id = ?";
        $stmtRequest = $conn->prepare($sqlRequest);
        $stmtRequest->bind_param('i', $customerId);
        $stmtRequest->execute();
        $stmtRequest->store_result();
        $stmtRequest->bind_result($requestId);
        $stmtRequest->fetch();

        if ($stmtRequest->num_rows > 0) {
            // Ensure the stmtRequest is closed after fetching the result
            $stmtRequest->close();

            // Insert transaction details into tbl_transaction
            $sqlTransaction = "INSERT INTO tbl_transaction (request_id, customer_id, service_option_id, service_option_name, customer_address, total_amount, delivery_fee, amount_tendered, money_change)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtTransaction = $conn->prepare($sqlTransaction);
            $stmtTransaction->bind_param('iiisssddd', $requestId, $customerId, $serviceId, $serviceOptionName, $address, $totalAmount, $deliveryFee, $amountTendered, $change);

            if ($stmtTransaction->execute()) {
                // Update pick up/delivery date in tbl_service_request
                $sqlPickUpDate = "UPDATE tbl_service_request SET request_datetime = ? WHERE request_id = ?";
                $stmtPickUpDate = $conn->prepare($sqlPickUpDate);
                $stmtPickUpDate->bind_param('si', $pickupDate, $requestId);

                if ($stmtPickUpDate->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Your service details saved successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update pickup date: ' . $stmtPickUpDate->error]);
                }
                $stmtPickUpDate->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save service details: ' . $stmtTransaction->error]);
            }
            $stmtTransaction->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Service request not found for customer']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
