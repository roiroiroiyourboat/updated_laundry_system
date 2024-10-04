<?php
if (isset($_GET['year']) && isset($_GET['month'])) {
    $year = intval($_GET['year']);
    $month = intval($_GET['month']);

    //calculate the first and last day of the month
    $firstDayOfMonth = new DateTime("$year-$month-01");
    $lastDayOfMonth = clone $firstDayOfMonth;
    $lastDayOfMonth->modify('last day of this month');

    $weeks = [];
    $currentDay = clone $firstDayOfMonth;
    $weekNumber = 1;

    while ($currentDay <= $lastDayOfMonth) {
        //start of the week sunday
        $startOfWeek = clone $currentDay;
        $startOfWeek->modify('Sunday this week');

        //end of the week is saturday
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');

        //adjust if end of the week  xceeds the last day of the month
        if ($endOfWeek > $lastDayOfMonth) {
            $endOfWeek = clone $lastDayOfMonth;
        }

        //week info
        $weeks[] = [
            'label' => "Week $weekNumber: " . $startOfWeek->format('Y-m-d') . " - " . $endOfWeek->format('Y-m-d'),
            'value' => "Week $weekNumber"
        ];

        //move to the next week
        $currentDay = clone $endOfWeek;
        $currentDay->modify('+1 day');
        $weekNumber++;
    }

    //return json
    header('Content-Type: application/json');
    echo json_encode($weeks);
}
?>
