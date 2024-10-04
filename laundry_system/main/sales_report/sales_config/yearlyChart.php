<?php
    //db connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "laundry_db"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //get the selected year from POST or default to current year
    $currentYear = date("Y");
    $selectedYear = isset($_POST['selectedYear']) ? $_POST['selectedYear'] : $currentYear;

    //query to fetch data
    $query = "
        SELECT 
            laundry_service_option,
            laundry_category_option,
            COUNT(*) AS order_count
        FROM 
            service_request
        WHERE 
            YEAR(service_request_date) = '$selectedYear'
            AND order_status = 'completed'
        GROUP BY 
            laundry_service_option, 
            laundry_category_option
    ";

    $result = $conn->query($query);

    //check if query was successful
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    //service colors and category shades
    $serviceColors = [
        'Wash/Dry/Fold' => 'rgba(255, 99, 132, ',  // Red
        'Wash/Dry/Press' => 'rgba(54, 162, 235, ',  // Blue
        'Dry Only' => 'rgba(255, 206, 86, ',     // Yellow
    ];

    $categoryShades = [
        'Clothes/Table Napkin/Pillowcase' => '0.8)',  // Slightly lighter
        'Bedsheet/Table Cloths/Curtain' => '0.6)',    // Lighter
        'Comforter/Bath towel' => '0.4)',             // Even lighter
    ];

    //generate random color for new service
    function getRandomColor() {
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        return "rgba($r, $g, $b, ";
    }

    //data for the chart
    $labels = [];
    $data = [];
    $backgroundColors = [];
    $borderColors = [];

    while ($row = $result->fetch_assoc()) {
        $service = $row['laundry_service_option'];
        $category = $row['laundry_category_option'];
        $labels[] = $service . ' - ' . $category;
        $data[] = $row['order_count'];

        //service color
        if (!isset($serviceColors[$service])) {
            $serviceColors[$service] = getRandomColor();
        }

        $shade = isset($categoryShades[$category]) ? $categoryShades[$category] : '1)';
        $backgroundColors[] = $serviceColors[$service] . $shade;
        $borderColors[] = $serviceColors[$service] . '1)';
    }

    // Close connection
    $conn->close();

    // Return data as JSON
    echo json_encode([
        'labels' => $labels,
        'data' => $data,
        'backgroundColors' => $backgroundColors,
        'borderColors' => $borderColors,
    ]);
?>