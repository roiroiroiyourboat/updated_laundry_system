<?php
    $conn = new mysqli('localhost', 'root', '', 'laundry_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $filter = isset($_POST['filter']) ? $_POST['filter'] : 'all';

    $date_condition = '';
    switch ($filter) {
        case 'daily':
            $date_condition = "DATE(sr.service_request_date) = CURDATE()";
            break;
        case 'weekly':
            $date_condition = "WEEK(sr.service_request_date) = WEEK(CURDATE())";
            break;
        case 'monthly':
            $date_condition = "MONTH(sr.service_request_date) = MONTH(CURDATE())";
            break;
        case 'yearly':
            $date_condition = "YEAR(sr.service_request_date) = YEAR(CURDATE())";
            break;
        case 'all':
            $date_condition = '1'; //no condition
            break;
    }

    // Query to fetch filtered transactions
    $query = "SELECT DISTINCT t.transaction_id, sr.service_request_date, c.customer_name, sr.laundry_service_option, sr.laundry_category_option,
                    sr.price, sr.weight, t.service_option_name, t.laundry_cycle, (t.delivery_fee + t.rush_fee) AS service_fee, 
                    t.total_amount, sr.order_status
            FROM transaction t
            JOIN service_request sr ON t.request_id = sr.request_id
            JOIN customer c ON t.customer_id = c.customer_id
            WHERE $date_condition";

    $result = $conn->query($query);

    // Query to sum total revenue
    $sum_query = "SELECT SUM(total_revenue) AS total_revenue
                FROM (
                    SELECT SUM(DISTINCT t.total_amount) AS total_revenue
                    FROM transaction t
                    JOIN service_request sr ON t.request_id = sr.request_id
                    WHERE $date_condition
                    GROUP BY sr.customer_order_id
                ) AS subquery";
    $sum_result = $conn->query($sum_query);
    $sum_row = $sum_result->fetch_assoc();
    $total_revenue = number_format($sum_row['total_revenue'], 2);

    $table_data = '';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $table_data .= "<tr>
                                <td>{$row['transaction_id']}</td>
                                <td>{$row['service_request_date']}</td>
                                <td>{$row['customer_name']}</td>
                                <td>{$row['laundry_service_option']}</td>
                                <td>{$row['laundry_category_option']}</td>
                                <td>₱{$row['price']}</td>
                                <td>{$row['weight']}kg</td>
                                <td>{$row['service_option_name']}</td>
                                <td>{$row['laundry_cycle']}</td>
                                <td>₱{$row['service_fee']}</td>
                                <td>₱{$row['total_amount']}</td>
                                <td>{$row['order_status']}</td>
                            </tr>";
        }
    } else {
        $table_data = "<tr><td colspan='12'>No records found</td></tr>";
    }

    // Return data as JSON
    echo json_encode([
        'table_data' => $table_data,
        'total_revenue' => $total_revenue
    ]);
?>
