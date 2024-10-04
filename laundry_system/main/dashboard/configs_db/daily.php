<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $conn = new mysqli('localhost', 'root', '', 'laundry_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //query to fetch data
    $query = "
        SELECT 
            DATE(service_request_date) AS order_date,
            laundry_service_option,
            laundry_category_option,
            COUNT(*) AS order_count
        FROM 
            service_request
        WHERE 
            DATE(service_request_date) = CURDATE()
            AND order_status = 'completed'
        GROUP BY 
            DATE(service_request_date), 
            laundry_service_option,
            laundry_category_option
    ";

    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $chartData = [
        'labels' => [],
        'data' => [],
        'backgroundColors' => [],
        'borderColors' => []
    ];

    // base colors for services
    $serviceColors = [
        'Wash/Dry/Fold' => 'rgba(255, 99, 132, ',  // Red
        'Wash/Dry/Press' => 'rgba(54, 162, 235, ',  // Blue
        'Dry Only' => 'rgba(255, 206, 86, ',     // Yellow
    ];

    // different opacity levels for categories
    $categoryShades = [
        'Clothes/Table Napkin/Pillowcase' => '0.8)',  // Slightly lighter
        'Bedsheet/Table Cloths/Curtain' => '0.6)',    // Lighter
        'Comforter/Bath towel' => '0.4)',             // Even lighter
    ];

    while ($row = $result->fetch_assoc()) {
        $chartData['labels'][] = $row['laundry_service_option'] . ' - ' . $row['laundry_category_option'];
        $chartData['data'][] = $row['order_count'];
        $baseColor = $serviceColors[$row['laundry_service_option']] ?? 'rgba(0, 0, 0, '; // default base color
        $shade = $categoryShades[$row['laundry_category_option']] ?? '0.3)';
        $color = $baseColor . $shade;
        $chartData['backgroundColors'][] = $color;
        $chartData['borderColors'][] = $color;
    }

    $conn->close();

    //rerun data as JSON
    header('Content-Type: application/json');
    echo json_encode($chartData);
?>
