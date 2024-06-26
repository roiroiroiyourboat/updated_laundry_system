<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root','','db_laundry');
if($conn->connect_error){
    die('Failed to connect : '.$conn->connect_error);
} else {
    $sql = "SELECT option_id, option_name FROM tbl_service_options";
    $result = $conn->query($sql);

    $service_option = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $service_option[] = [
                'option_id' => $row['option_id'],
                'option_name' => $row['option_name']
            ];
        }
    }
    echo json_encode($service_option);
}

$conn->close();
?>
