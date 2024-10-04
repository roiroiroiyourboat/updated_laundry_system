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

                            if ($conn->connect_error) {
                                echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
                                exit;
                            }

                            //query to count pick up requests for today
                            $qry = "
                                SELECT COUNT(DISTINCT sr.customer_id) AS total_requests
                                FROM service_request sr
                                JOIN transaction t 
                                ON sr.customer_id = t.customer_id
                                WHERE DATE(sr.request_date) = CURDATE()
                                AND t.service_option_name = 'Customer Pick-Up'
                            ";
                            $qry_run = $conn->query($qry);

                            if (!$qry_run) {
                                echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
                                exit;
                            }

                            $row = $qry_run->fetch_assoc();

                            echo '<h2>' . $row['total_requests'] . '</h2>';
                            
                            $conn->close();
                        ?>
                        </p>
                    </div>

                    <div class="card card-body p-3">
                        <h4>Delivery Requests</h4>
                        <h5 id="delivery-orders">
                        <?php 
                            $conn = new mysqli('localhost', 'root', '', 'laundry_db');

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

                            if (!$qry_run) {
                                echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
                                exit;
                            }

                            $row = $qry_run->fetch_assoc();

                            echo '<h2>' . $row['total_requests'] . '</h2>';
  
                            $conn->close();
                        ?>
                        </h5>
                    </div>

                    <div class="card card-body p-3">
                        <h4>Rush Requests</h4>
                        <h5 id="rush-orders">
                        <?php 
                            $conn = new mysqli('localhost', 'root', '', 'laundry_db');

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

                            //check for query errors
                            if (!$qry_run) {
                                echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
                                exit;
                            }

                            //fetch result
                            $row = $qry_run->fetch_assoc();

                            //display the count of rush requests
                            echo '<h2>' . $row['total_requests'] . '</h2>';

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
                        </div>

                        <!--------------------------------YEAR CHART----------------------------->
                        <div class="chart" id="yearlyChart" >
                            <h4>Service Requests in Year</h4>
                            <canvas id="yearchart"></canvas>
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
                        $conn = new mysqli('localhost', 'root', '', 'laundry_db');

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

                    //close connection
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
                                //check if event is present on that day
                                const eventDate = new Date(year, month, i);
                                const eventsForDay = <?php echo json_encode($events); ?>.filter((event) => {
                                    const eventDateTime = new Date(event.start);
                                    return eventDateTime.getDate() === i && eventDateTime.getMonth() === month && eventDateTime.getFullYear() === year;
                                });

                                if (eventsForDay.length > 0) {
                                    days += `<div class="day has-event mark">${i}</div>`; //add the mark
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
                                        <div class="event">
                                        <h4><li>${event.title}</li></h4>
                                        <span>Start: ${event.start}</span>
                                        <span>End: ${event.end}</span>
                                        </div>
                                        <hr style="border: 1px solid #b8c1ec; margin: 5px 0;"> 
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