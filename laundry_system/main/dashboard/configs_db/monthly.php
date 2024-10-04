<?php
    header('Content-Type: application/json');

    $conn = new mysqli('localhost', 'root', '', 'laundry_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //query to fetch current month's orders by service option and category
    $query = "
        SELECT 
            laundry_service_option,
            laundry_category_option,
            COUNT(*) AS order_count
        FROM 
            service_request
        WHERE 
            MONTH(service_request_date) =  MONTH(CURDATE())
            AND order_status = 'completed'
        GROUP BY 
            laundry_service_option, 
            laundry_category_option
    ";

    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }                           

    // to store the data for the chart
    $chartData = [
        'labels' => [],
        'data' => [],
        'backgroundColors' => [],
        'borderColors' => []
    ];

    //colors for services
    $serviceColors = [
        'Wash/Dry/Fold' => 'rgba(255, 99, 132, ',  // Red
        'Wash/Dry/Press' => 'rgba(54, 162, 235, ',  // Blue
        'Dry Only' => 'rgba(255, 206, 86, ',        // Yellow
    ];

    // Opacity levels for categories
    $categoryShades = [
        'Clothes/Table Napkin/Pillowcase' => '0.8)',  // Slightly lighter
        'Bedsheet/Table Cloths/Curtain' => '0.6)',    // Lighter
        'Comforter/Bath towel' => '0.4)',             // Even lighter
    ];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fullLabel = $row['laundry_service_option'] . ' - ' . $row['laundry_category_option'];
            $chartData['labels'][] = $fullLabel;
            $chartData['data'][] = $row['order_count'];
            $baseColor = $serviceColors[$row['laundry_service_option']] ?? 'rgba(0, 0, 0, '; //default base color
            $shade = $categoryShades[$row['laundry_category_option']] ?? '0.3)';
            $color = $baseColor . $shade;
            $chartData['backgroundColors'][] = $color;
            $chartData['borderColors'][] = $color;
        }
    } else {
        $chartData['labels'][] = 'No data';
        $chartData['data'][] = 0;
        $chartData['backgroundColors'][] = 'rgba(0, 0, 0, 0.1)';
        $chartData['borderColors'][] = 'rgba(0, 0, 0, 0.1)';
    }

    $conn->close();

    //return data as json object
    echo json_encode($chartData);
?>
