<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'root', '', 'laundry_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch weekly data
$query = "
    SELECT 
        laundry_service_option,
        laundry_category_option,
        COUNT(*) AS order_count
    FROM 
        service_request
    WHERE 
        YEARWEEK(service_request_date, 0) = YEARWEEK(CURDATE(), 0)
        AND order_status = 'completed'
    GROUP BY 
        laundry_service_option,
        laundry_category_option
";

$result = $conn->query($query);

$chartData = [
    'labels' => [],
    'data' => [],
    'backgroundColors' => [],
    'borderColors' => []
];

// Base colors for services
$serviceColors = [
    'Wash/Dry/Fold' => 'rgba(255, 99, 132, ',  // Red
    'Wash/Dry/Press' => 'rgba(54, 162, 235, ',  // Blue
    'Dry Only' => 'rgba(255, 206, 86, ',     // Yellow
];

// Different opacity level of base color for category
$categoryShades = [
    'Clothes/Table Napkin/Pillowcase' => '0.8)',  // Slightly lighter
    'Bedsheet/Table Cloths/Curtain' => '0.6)',    // Lighter
    'Comforter/Bath towel' => '0.4)',             // Even lighter
];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Service and category options as labels
        $chartData['labels'][] = $row['laundry_service_option'] . ' - ' . $row['laundry_category_option'];
        $chartData['data'][] = $row['order_count'];

        // Get the base color for the service
        $baseColor = $serviceColors[$row['laundry_service_option']] ?? 'rgba(0, 0, 0, '; // Default base color

        // Get the shade for the category, fallback to 0.3 opacity if not found
        $shade = $categoryShades[$row['laundry_category_option']] ?? '0.3)';

        // Combine base color with the category's shade
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

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($chartData);
?>
