<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
      <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
      integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"/>
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <!---CHART--->
    <script src ="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    
   
</head>
<body>
    <div class="progress"></div>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button id="toggle-btn" type="button">
                    <i class="bx bx-menu-alt-left"></i>
                </button>

                <div class="sidebar-logo">
                    <a href="#">Azia Skye</a>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="/laundry_system/main/dashboard/dashboard.php" class="sidebar-link">
                        <i class="lni lni-grid-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="/laundry_system/main/my_profile/profile.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
                    </a>
                </li>

                <li class="sidebar-item">
                   <a href="/laundry_system/main/users/users.php" class="sidebar-link">
                        <i class="lni lni-users"></i>
                        <span>Users</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                        data-bs-target="#records" aria-expanded="false" aria-controls="records">
                        <i class="lni lni-files"></i>
                        <span>Records</span>
                    </a>

                    <ul id="records" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="/laundry_system/main/records/customer.php" class="sidebar-link">Customer</a>
                        </li>

                        <li class="sidebar-item">
                            <a href="/laundry_system/main/records/service.php" class="sidebar-link">Service</a>
                        </li>

                        <li class="sidebar-item">
                            <a href="/laundry_system/main/records/category.php" class="sidebar-link">Category</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a href="/laundry_system/main/transaction/transaction.php" class="sidebar-link">
                        <i class="lni lni-coin"></i>
                        <span>Transaction</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="/laundry_system/main/sales_report/report.php" class="sidebar-link">
                        <i class='bx bx-line-chart'></i>
                        <span>Sales Report</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="/laundry_system/main/settings/settings.php" class="sidebar-link">
                        <i class="lni lni-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>

                <hr style="border: 1px solid #b8c1ec; margin: 8px">

                    <li class="sidebar-item">
                        <a href="/laundry_system/main/archived/archived.php" class="sidebar-link">
                            <i class='bx bxs-archive-in'></i>
                            <span class="nav-item">Archived</span>
                        </a>
                    </li>

            </ul>

            <div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
        <!-------------MAIN CONTENT------------->
        <div class="main p-3">
            <div class="header-con">
                <h1>
                   Dashboard
                </h1>
            </div>
                 <!----CARDS FOR SERVICE TYPE ORDERS (RUSH/PICK UP/DELIVERY) ---->
                <div class="cards">
                    <div class="card card-body p-3">
                        <h4>Customer Pick-Up</h4>
                        <h5 id="pickup-orders">
                        <?php 
                            $conn = new mysqli('localhost', 'root', '', 'laundry_db');

                            // Check connection
                            if ($conn->connect_error) {
                                echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
                                exit;
                            }

                            // query to count pick up requests for today
                            $qry = "
                                SELECT COUNT(DISTINCT sr.customer_id) AS total_requests
                                FROM service_request sr
                                JOIN transaction t 
                                ON sr.customer_id = t.customer_id
                                WHERE DATE(sr.request_date) = CURDATE()
                                AND t.service_option_name = 'Customer Pick-Up'
                            ";
                            $qry_run = $conn->query($qry);

                            // Check for query errors
                            if (!$qry_run) {
                                echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
                                exit;
                            }

                            // Fetch the result
                            $row = $qry_run->fetch_assoc();

                            // Display the count of pick-up requests
                            echo '<h2>' . $row['total_requests'] . '</h2>';
                            
                            // Close the connection
                            $conn->close();
                        ?>
                        </p>
                    </div>

                    <div class="card card-body p-3">
                        <h4>Delivery Requests</h4>
                        <h5 id="delivery-orders">
                        <?php 
                            $conn = new mysqli('localhost', 'root', '', 'laundry_db');

                            // Check connection
                            if ($conn->connect_error) {
                                echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
                                exit;
                            }

                            //query to count delivery requests for today
                            $qry = "
                                SELECT COUNT(DISTINCT sr.customer_id) AS total_requests
                                FROM service_request sr
                                JOIN transaction t 
                                ON sr.customer_id = t.customer_id
                                WHERE DATE(sr.request_date) = CURDATE()
                                AND t.service_option_name = 'Delivery'
                            ";

                            $qry_run = $conn->query($qry);

                            // Check for query errors
                            if (!$qry_run) {
                                echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
                                exit;
                            }

                            // Fetch the result
                            $row = $qry_run->fetch_assoc();

                            // Display the count of pick-up requests
                            echo '<h2>' . $row['total_requests'] . '</h2>';
                            
                            // Close the connection
                            $conn->close();
                        ?>
                        </h5>
                    </div>

                    <div class="card card-body p-3">
                        <h4>Rush Requests</h4>
                        <h5 id="rush-orders">
                        <?php 
                            $conn = new mysqli('localhost', 'root', '', 'laundry_db');

                            // Check connection
                            if ($conn->connect_error) {
                                echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
                                exit;
                            }

                            //query to count rush requests for today
                            $qry = "
                                SELECT COUNT(DISTINCT sr.customer_id) AS total_requests
                                FROM service_request sr
                                JOIN transaction t 
                                ON sr.customer_id = t.customer_id
                                WHERE DATE(sr.request_date) = CURDATE()
                                AND t.laundry_cycle = 'Rush'
                            ";

                            $qry_run = $conn->query($qry);

                            // Check for query errors
                            if (!$qry_run) {
                                echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
                                exit;
                            }

                            // Fetch the result
                            $row = $qry_run->fetch_assoc();

                            // Display the count of rush requests
                            echo '<h2>' . $row['total_requests'] . '</h2>';
                            
                            // Close the connection
                            $conn->close();
                        ?>
                        </h5>
                    </div>
                </div>

                <!------------------CHARTS----------------------->
                <div class="charts-container">
                    <div class="charts">
                        <!----------------------------ORDERS IN DAY----------------------------------->
                        <div class="chart" id="weeklyChart">
                            <h4>Service Requests in Day</h4>
                            <canvas id="daychart"></canvas>
                            <div id="chart_dialog" title="View Chart"></div>
                        </div>
                        
                        <!-------------------------------------ORDERS IN WEEK---------------------------------------->          
                        <div class="chart" id="weeklyChart">
                            <h4>Service Requests in Week</h4>
                            <canvas id="weekchart"></canvas> 
                        </div>
                        <!----------------------------------------ORDERS IN MONTH---------------------------------->
                        <div class="chart" id="monthlyChart">
                            <h4>Service Requests in Month</h4>
                            <canvas id="monthchart"></canvas>
                            <?php
                                // Connect to database
                                $conn = new mysqli('localhost', 'root', '', 'laundry_db');

                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // query to fetch current month's orders by service option and category //$selectedMonth AND YEAR(service_request_datetime) = $currentYear
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

                                // array to store the data for the chart
                                $labels = [];
                                $data = [];
                                $backgroundColors = [];
                                $borderColors = [];

                                // base colors for services
                                $serviceColors = [
                                    'Wash/Dry/Fold' => 'rgba(255, 99, 132, ',  // Red
                                    'Wash/Dry/Press' => 'rgba(54, 162, 235, ',  // Blue
                                    'Dry Only' => 'rgba(255, 206, 86, ',     // Yellow
                                ];

                                // opacity levels for categories
                                $categoryShades = [
                                    'Clothes/Table Napkin/Pillowcase' => '0.8)',  // Slightly lighter
                                    'Bedsheet/Table Cloths/Curtain' => '0.6)',    // Lighter
                                    'Comforter/Bath towel' => '0.4)',             // Even lighter
                                ];

                                if ($result && $result-> num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $fullLabel = $row['laundry_service_option'] . ' - ' . $row['laundry_category_option'];
                                        $labels[] = $fullLabel;
                                        $data[] = $row['order_count'];
                                        $baseColor = $serviceColors[$row['laundry_service_option']] ?? 'rgba(0, 0, 0, '; // default base color
                                        $shade = $categoryShades[$row['laundry_category_option']] ?? '0.3)';
                                        $color = $baseColor . $shade;
                                        $backgroundColors[] = $color;
                                        $borderColors[] = $color;
                                    }
                                } else {
                                    $chartData['labels'][] = 'No data';
                                    $chartData['data'][] = 0;
                                    $chartData['backgroundColors'][] = 'rgba(0, 0, 0, 0.1)';
                                    $chartData['borderColors'][] = 'rgba(0, 0, 0, 0.1)';
                                }

                                // Close connection
                                $conn->close();
                            ?>
   
                            <script>                               
                                document.addEventListener("DOMContentLoaded", function() {
                                    const ctx = document.getElementById('monthchart').getContext('2d');
                                    const monthchart = new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: <?php echo json_encode($labels); ?>,
                                            datasets: [{
                                                label: 'Orders in the Month',
                                                data: <?php echo json_encode($data); ?>,
                                                backgroundColor: <?php echo json_encode($backgroundColors); ?>,
                                                borderColor: <?php echo json_encode($borderColors); ?>,
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    position: 'bottom',
                                                },
                                                title: {
                                                    display: true,
                                                    text: 'Monthly Requests by Service and Category',
                                                    font: {
                                                        size: 14
                                                    }
                                                },
                                                tooltip: {
                                                    callbacks: {
                                                        label: function(tooltipItem) {
                                                            let label = tooltipItem.label || '';
                                                            let value = tooltipItem.raw || '';
                                                            return [
                                                                `${label}: ${value}`, 
                                                                `Requests Count: ${value}` 
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>
                        </div>

                        <!--------------------------------YEAR CHART----------------------------->
                        <div class="chart" id="yearlyChart" >
                            <h4>Service Requests in Year</h4>
                            <canvas id="yearchart"></canvas>

                            <?php
                                // Connect to database
                                $servername = "localhost";
                                $username = "root";
                                $password = "";
                                $dbname = "laundry_db"; 

                                $conn = new mysqli($servername, $username, $password, $dbname);

                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // Query to fetch current year orders by service option and category
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

                                // Check if query was successful
                                if (!$result) {
                                    die("Query failed: " . $conn->error);
                                }

                                // Service colors and category shades
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

                                // Function to generate a random RGBA color
                                function getRandomColor() {
                                    $r = rand(0, 255);
                                    $g = rand(0, 255);
                                    $b = rand(0, 255);
                                    return "rgba($r, $g, $b, ";
                                }

                                // arrays to store data
                                $labels = [];
                                $data = [];
                                $backgroundColors = [];
                                $borderColors = [];

                                // Fetch data from query
                                while ($row = $result->fetch_assoc()) {
                                    $service = $row['laundry_service_option'];
                                    $category = $row['laundry_category_option'];
                                    $labels[] = $service . ' - ' . $category;
                                    $data[] = $row['order_count'];

                                    // Check if the service exists in the serviceColors array
                                    if (!isset($serviceColors[$service])) {
                                        // If the service is new, assign a random color
                                        $serviceColors[$service] = getRandomColor();
                                    }

                                    // If the category doesn't exist in categoryShades, assign a default shade
                                    $shade = isset($categoryShades[$category]) ? $categoryShades[$category] : '1)';

                                    // Assign colors
                                    $backgroundColors[] = $serviceColors[$service] . $shade;
                                    $borderColors[] = $serviceColors[$service] . '1)';  
                                }

                                // Close connection
                                $conn->close();
                            ?>


                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    const labels = <?php echo json_encode($labels); ?>;
                                    const data = {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Requests in Year',
                                            data: <?php echo json_encode($data); ?>,
                                            backgroundColor: <?php echo json_encode($backgroundColors); ?>,
                                            borderColor: <?php echo json_encode($borderColors); ?>,
                                            borderWidth: 5,
                                            fill: true, 
                                        }]
                                    };

                                    const config = {
                                        type: 'line',
                                        data: data,
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    position: 'top',
                                                },
                                                title: {
                                                    display: true,
                                                    text: 'Yearly Requests by Service and Category',
                                                    font: {
                                                        size: 14
                                                    }
                                                },
                                            },
                                            scales: {
                                                x: {
                                                    ticks: {
                                                        callback: function(value, index, values) {
                                                            // truncate labels if they are longer than 15 characters
                                                            return value.length > 15 ? value.substr(0, 15) + '...' : value;
                                                        }
                                                    }
                                                },
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    };

                                    var yearchart = new Chart(
                                        document.getElementById('yearchart'),
                                        config
                                    );
                                });
                            </script>
                        </div>

                    </div> <!--end of charts-->   
                </div> <!--end of charts-container-->
                
                <!--------------CALENDAR------------------->
                <div class="container">
                    <div class="left">
                        <div class="calendar">
                        <div class="month">
                            <i class="fas fa-angle-left prev"></i>
                            <div class="date">December 2015</div>
                            <i class="fas fa-angle-right next"></i>
                        </div>
                        <div class="weekdays">
                            <div>Sun</div>
                            <div>Mon</div>
                            <div>Tue</div>
                            <div>Wed</div>
                            <div>Thu</div>
                            <div>Fri</div>
                            <div>Sat</div>
                        </div>
                        <div class="days"></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="event-title">Events</div>
                        <hr>
                        <div class="events"></div>
                    </div>

                    <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "laundry_db";

                    // Create connection
                        $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $query = "SELECT request_id, laundry_service_option, request_date, service_request_date FROM service_request";
                        $result = $conn->query($query);

                        if (!$result) {
                            die("Query failed: " . $conn->error);
                        }

                                    
                    $events = array();

                    while ($row = $result->fetch_assoc()) {
                        $events[] = array(
                            'title' => $row['laundry_service_option'],
                            'start' => $row['service_request_date'],
                            'end' => $row['request_date'],
                        );
                    }

                    // Close connection
                    $conn->close();
                    ?>

                    <script>
                        const calendar = document.querySelector(".calendar"),
                            date = document.querySelector(".date"),
                            daysContainer = document.querySelector(".days"),
                            prev = document.querySelector(".prev"),
                            next = document.querySelector(".next");

                        let today = new Date();
                        let activeDay;
                        let month = today.getMonth();
                        let year = today.getFullYear();

                        const months = [
                            "January",
                            "February",
                            "March",
                            "April",
                            "May",
                            "June",
                            "July",
                            "August",
                            "September",
                            "October",
                            "November",
                            "December",
                        ];

                        // Function to add days in days with class day and prev-date next-date on previous month and next month days and active on today
                        function initCalendar() {
                            const firstDay = new Date(year, month, 1);
                            const lastDay = new Date(year, month + 1, 0);
                            const prevLastDay = new Date(year, month, 0);
                            const prevDays = prevLastDay.getDate();
                            const lastDate = lastDay.getDate();
                            const day = firstDay.getDay();
                            const nextDays = 7 - lastDay.getDay() - 1;

                            date.innerHTML = months[month] + " " + year;

                            let days = "";
                            // Prev
                            for (let x = day; x > 0; x--) {
                                days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
                            }

                            for (let i = 1; i <= lastDate; i++) {
                                // Check if event is present on that day
                                
                                const eventDate = new Date(year, month, i);
                                const eventsForDay = <?php echo json_encode($events); ?>.filter((event) => {
                                    const eventDateTime = new Date(event.start);
                                    return eventDateTime.getDate() === i && eventDateTime.getMonth() === month && eventDateTime.getFullYear() === year;
                                });

                                if (eventsForDay.length > 0) {
                                    days += `<div class="day has-event mark">${i}</div>`; // Add the mark class
                                } else if (
                                    i === new Date().getDate() &&
                                    year === new Date().getFullYear() &&
                                    month === new Date().getMonth()
                                ) {
                                    days += `<div class="day today">${i}</div>`;
                                } else {
                                    days += `<div class="day">${i}</div>`;
                                }
                            }

                            for (let j = 1; j < nextDays; j++) {
                                days += `<div class="day next-date">${j}</div>`;
                            }
                            
                            daysContainer.innerHTML = days;

                            
                        }

                        initCalendar();

                        function prevMonth() {
                            month--;
                            if (month < 0) {
                                month = 11;
                                year--;
                            }      
                            initCalendar();
                        }

                        function nextMonth() {
                            month++;
                            if (month > 11) {
                                month = 0;
                                year++;
                            }
                            initCalendar();
                        }

                        prev.addEventListener("click", prevMonth);
                        next.addEventListener("click", nextMonth);

                        function displayEventsForDate(date, events) {
                            const eventsContainer = document.querySelector(".events");
                            let eventList = "";

                            events.forEach((event) => {
                                const eventDate = new Date(event.start);
                                if (eventDate.getDate() === date.getDate() && eventDate.getMonth() === date.getMonth() && eventDate.getFullYear() === date.getFullYear()) {
                                    eventList += `
                                        <hr><div class="event">
                                        <h4><li>${event.title}</li></h4>
                                        <p>Start: ${event.start}</p>
                                        <p>End: ${event.end}</p>
                                        </div></hr>
                                    `;
                                }
                            });

                            eventsContainer.innerHTML = eventList;
                        }

                        daysContainer.addEventListener("click", (e) => {
                            if (e.target.classList.contains("day")) {
                                const day = parseInt(e.target.textContent);
                                const date = new Date(year, month, day);
                                displayEventsForDate(date, <?php echo json_encode($events); ?>);
                            }
                        });

                        displayEventsForDate(new Date().getDate(), <?php echo json_encode($events); ?>);
                    </script>
                </div> <!--END OF CALENDAR CONTAINER-->

        </div> <!--end of main content-->
    </div><!--end of wrapper--->
    
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="dashboard.js"></script>
</body>
</html>