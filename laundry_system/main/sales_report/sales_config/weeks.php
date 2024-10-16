<?php
if (isset($_GET['year']) && isset($_GET['month'])) {
    $year = intval($_GET['year']);
    $month = intval($_GET['month']);

    // Validate input values
    if ($year < 1900 || $year > 2100 || $month < 1 || $month > 12) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Invalid input values']);
        exit;
    }

    // Create DateTime objects
    $firstDayOfMonth = new DateTime("$year-$month-01");
    $lastDayOfMonth = new DateTime("$year-$month-" . date('t', mktime(0, 0, 0, $month, 1, $year)));

    $weeks = [];
    $currentDay = clone $firstDayOfMonth;
    $weekNumber = 1;

    //get the day of the week for the first day (1=Monday, 7=Sunday)
    $firstDayOfWeek = intval($firstDayOfMonth->format('N'));

    //start to the first Sunday before or on the first day of the month
    if ($firstDayOfWeek != 7) {
        $currentDay->modify('last Sunday');
    }

    while ($currentDay <= $lastDayOfMonth) {
        $startOfWeek = clone $currentDay;
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');

        if ($endOfWeek > $lastDayOfMonth) {
            $endOfWeek = clone $lastDayOfMonth;
        }

        $weeks[] = [
            'label' => "Week $weekNumber: " . $startOfWeek->format('Y-m-d') . " - " . $endOfWeek->format('Y-m-d'),
            'value' => "Week $weekNumber"
        ];

        $currentDay = clone $endOfWeek;
        $currentDay->modify('+1 day');
        $weekNumber++;
    }

    // Return JSON
    header('Content-Type: application/json');
    echo json_encode($weeks);
}
?>
