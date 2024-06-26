<?php
session_start();
header('Content-Type: application/json');

try {
    $customer_name = $_POST['customer_name'];
    $contact_number = $_POST['contact_number'];

    $conn = new mysqli('localhost', 'root', '', 'db_laundry');

    if ($conn->connect_error) {
        throw new Exception('Connection Failed: ' . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("SELECT customer_id FROM tbl_customer WHERE customer_name = ? AND contact_number = ?");
        $stmt->bind_param("si", $customer_name, $contact_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customer_id = $row['customer_id'];
            echo json_encode(['status' => 'success', 'customer_id' => $customer_id]);
        } else {
            $stmt = $conn->prepare("INSERT INTO tbl_customer (customer_name, contact_number) VALUES (?, ?)");
            $stmt->bind_param("si", $customer_name, $contact_number);
            if ($stmt->execute()) {
                $customer_id = $stmt->insert_id;
                echo json_encode(['status' => 'success', 'customer_id' => $customer_id]);
            } else {
                throw new Exception('Error inserting customer data');
            }
        }
        $stmt->close();
    }
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
