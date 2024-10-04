<?php
    header('Content-Type: application/json'); //to indicate json response

    $conn = new mysqli('localhost', 'root', '', 'laundry_db');

    if ($conn->connect_error) {
        die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
    }

    //to fetch current year orders by service option and category
    $query = "
        SELECT 
            laundry_service_option,
            laundry_category_option,
            COUNT(*) AS order_count
        FROM 
            service_request
        WHERE 
            YEAR(service_request_date) = YEAR(CURDATE())
            AND order_status = 'completed'
        GROUP BY 
            laundry_service_option, 
            laundry_category_option
    ";

    $result = $conn->query($query);

    if (!$result) {
        die(json_encode(['error' => "Query failed: " . $conn->error]));
    }

    //service colors and category shades
    $serviceColors = [
        'Wash/Dry/Fold' => 'rgba(255, 99, 132, ',  // Red
        'Wash/Dry/Press' => 'rgba(54, 162, 235, ',  // Blue
        'Dry Only' => 'rgba(255, 206, 86, ',        // Yellow
    ];

    $categoryShades = [
        'Clothes/Table Napkin/Pillowcase' => '0.8)',  // Slightly lighter
        'Bedsheet/Table Cloths/Curtain' => '0.6)',    // Lighter
        'Comforter/Bath towel' => '0.4)',             // Even lighter
    ];

    // to generate a random RGBA color
    function getRandomColor() {
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        return "rgba($r, $g, $b, ";
    }

    $labels = [];
    $data = [];
    $backgroundColors = [];
    $borderColors = [];

    //data from query
    while ($row = $result->fetch_assoc()) {
        $service = $row['laundry_service_option'];
        $category = $row['laundry_category_option'];
        $labels[] = $service . ' - ' . $category;
        $data[] = $row['order_count'];

        //check if the service exists in the serviceColors array
        if (!isset($serviceColors[$service])) {
            //if service is new, assign random color
            $serviceColors[$service] = getRandomColor();
        }

        //if the category does not exist, assign the default color
        $shade = isset($categoryShades[$category]) ? $categoryShades[$category] : '1)';

        //assign colors
        $backgroundColors[] = $serviceColors[$service] . $shade;
        $borderColors[] = $serviceColors[$service] . '1)';  
    }

    $conn->close();

    //data to return as JSON
    $chartData = [
        'labels' => $labels,
        'data' => $data,
        'backgroundColors' => $backgroundColors,
        'borderColors' => $borderColors,
    ];

    echo json_encode($chartData);
?>
