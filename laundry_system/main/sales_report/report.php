<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!--CHARTS-->
    <script src ="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" 
    integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="report.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    Sales Report
                </h1>
            </div>

            <!------------------CHARTS----------------------->
            <div class="charts-container">
                    <div class="charts">
                        <!----------------------------ORDERS IN DAY----------------------------------->
                        <div class="chart" id="dailyChart">
                            <div class="chart-header">
                                 <h5>Service Requests <br> in Day</h5>
                                 <button id="Btn" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#dayModal">
                                    <i class='bx bx-menu'></i></button>
                            </div>
                           
                            <input type="date" class="form-control" id="chooseDate" value="" onchange="updateDayChart()">
                            <canvas id="daychart"></canvas>
                            <div id="chart_dialog" title="View Chart"></div>

                            <!-- Modal -->
                            <div class="modal fade" id="dayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="dayModalLabel">Daily Service Requests</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <canvas id="dayChartModalCanvas"></canvas>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="downloadChart()">Download Chart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!----------JS---------->
                            <script>
                                 function getCurrentDate() {
                                    const today = new Date();
                                    const year = today.getFullYear();
                                    const month = String(today.getMonth() + 1).padStart(2, '0'); 
                                    const day = String(today.getDate()).padStart(2, '0');
                                    return `${year}-${month}-${day}`;
                                }
     
                                document.addEventListener('DOMContentLoaded', function () {
                                    const datePicker = document.getElementById('chooseDate');
                                    datePicker.value = getCurrentDate();
                                    updateDayChart();

                                    datePicker.addEventListener('change', updateDayChart);;                             
                                });

                                function updateDayChart() {
                                    const selectedDate = document.getElementById('chooseDate').value;
                                    
                                    fetch(`/laundry_system/main/sales_report/sales_config/dailyChart.php?date=${selectedDate}`)
                                    .then(response => {
                                        if (!response.ok) {  //check if the response status is OK
                                            throw new Error(`HTTP error! status: ${response.status}`);
                                        }
                                        return response.json();  // If response is OK, parse the JSON
                                    })
                                    .then(data => {
                                        const ctx = document.getElementById('daychart').getContext('2d');
                                        
                                        // Destroy previous chart instance, if any
                                        if (window.daychart && typeof window.daychart.destroy === 'function') {
                                            window.daychart.destroy();
                                        }

                                        // Create new chart
                                        window.daychart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: data.labels,
                                                datasets: [{
                                                    label: 'Requests in Day',
                                                    data: data.data,
                                                    backgroundColor: data.backgroundColors,
                                                    borderColor: data.borderColors,
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    title: {
                                                        display: true,
                                                        text: `Daily Requests for ${selectedDate}`,
                                                        font: {
                                                            size: 14 
                                                        }
                                                    },
                                                    legend: {
                                                        display: true,
                                                        position: 'top',
                                                        labels: {
                                                            font: {
                                                                size: 12 
                                                            }
                                                        }
                                                    },
                                                },
                                                scales: {
                                                    x: {
                                                        ticks: {
                                                            callback: function(value) {
                                                                return value.length > 15 ? value.substr(0, 15) + '...' : value;
                                                            }
                                                        }
                                                    },
                                                    y: {
                                                        beginAtZero: true
                                                    }
                                                }
                                            }
                                        });

                                        renderModalChart(data, selectedDate);
                                    })
                                    .catch(error => {
                                        console.error('Error fetching chart data:', error);
                                        alert('There was an error fetching the data. Please try again later.');
                                    });
                                }

                                function renderModalChart(data, selectedDate) {
                                    const ctx = document.getElementById('dayChartModalCanvas').getContext('2d');
                                    
                                    if (window.dayChartModal && typeof window.dayChartModal.destroy === 'function') {
                                        window.dayChartModal.destroy();
                                    }

                                    window.dayChartModal = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: data.labels,
                                            datasets: [{
                                                label: 'Requests in Day',
                                                data: data.data,
                                                backgroundColor: data.backgroundColors,
                                                borderColor: data.borderColors,
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                title: {
                                                    display: true,
                                                    text: `Daily Requests for ${selectedDate}`,
                                                    font: {
                                                        size: 14 
                                                    }
                                                },
                                                legend: {
                                                    display: true,
                                                    position: 'top',
                                                    labels: {
                                                        font: {
                                                            size: 12 
                                                        }
                                                    }
                                                }
                                            },
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                }

                                function downloadChart() {
                                    const canvas = document.getElementById('dayChartModalCanvas');
                                    const link = document.createElement('a');
                                    link.href = canvas.toDataURL('image/png');  
                                    link.download = 'daily-chart.png'; 
                                    link.click();
                                }
                            </script>
                        </div>

                        <!-------------------------------------ORDERS IN WEEK---------------------------------------->          
                        <div class="chart" id="weeklyChart">
                            <div class="chart-header">
                                <h5>Service Requests <br> in Week</h4>
                                <button id="Btn" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#weekModal">
                                <i class='bx bx-menu'></i></button>
                            </div>
                        
                                <div class="selector">
                                    <!---SELECT YEAR--->
                                    <select id="chooseYear" class="form-select" onchange="populateYears()">
                                    </select>

                                    <!---SELECT MONTH--->
                                    <select id="chooseMonth" class="form-select" onchange="populateMonths()">
                                    </select>

                                    <!---SELECT WEEK--->
                                    <select id="chooseWeek" class="form-select" onchange="loadWeekData(this.value)">
                                    </select>
                                </div>
                            <canvas id="weekchart"></canvas>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="weekModal" tabindex="-1" aria-labelledby="weekModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="weekModalLabel">Weekly Service Requests</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <canvas id="weekChartModalCanvas"></canvas>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="downloadWeekChart()">Download Chart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-----------JS----------->
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    populateYears();
                                    populateMonths();
                                    populateWeeks();
                                });

                                function populateYears() {
                                    const chooseYear = document.getElementById('chooseYear');
                                    const currentYear = new Date().getFullYear();
                                    
                                    for (let year = currentYear - 5; year <= currentYear + 5; year++) {
                                        const option = document.createElement('option');
                                        option.value = year;
                                        option.text = year;
                                        if (year === currentYear) option.selected = true;
                                        chooseYear.appendChild(option);
                                    }
                                }

                                function populateMonths() {
                                    const chooseMonth = document.getElementById('chooseMonth');
                                    const months = [
                                        "January", "February", "March", "April", "May", "June",
                                        "July", "August", "September", "October", "November", "December"
                                    ];
                                    const currentMonth = new Date().getMonth(); // Get current month index (0-11)

                                    months.forEach((month, index) => {
                                        const option = document.createElement('option');
                                        option.value = index + 1; // Month values as 1-12
                                        option.text = month;
                                        if (index === currentMonth) option.selected = true;
                                        chooseMonth.appendChild(option);
                                    });
                                }

                                function populateWeeks() {
                                    const chooseYear = document.getElementById('chooseYear').value;
                                    const chooseMonth = document.getElementById('chooseMonth').value;
                                    const chooseWeek = document.getElementById('chooseWeek');
                                    chooseWeek.innerHTML = ''; // Clear previous weeks

                                    const year = parseInt(chooseYear);
                                    const month = parseInt(chooseMonth) - 1;
                                    const firstDayOfMonth = new Date(year, month, 1);
                                    const lastDayOfMonth = new Date(year, month + 1, 0);

                                    let currentDay = firstDayOfMonth;
                                    let weekNumber = 1;

                                    while (currentDay <= lastDayOfMonth) {
                                        const startOfWeek = new Date(currentDay);
                                        startOfWeek.setDate(currentDay.getDate() - currentDay.getDay()); // Start of the week (Sunday)
                                        const endOfWeek = new Date(startOfWeek);
                                        endOfWeek.setDate(startOfWeek.getDate() + 6); // End of the week (Saturday)

                                        if (endOfWeek > lastDayOfMonth) {
                                            endOfWeek.setDate(lastDayOfMonth.getDate());
                                        }

                                        const option = document.createElement('option');
                                        option.value = `Week ${weekNumber}`;
                                        option.text = `Week ${weekNumber}: ${startOfWeek.toLocaleDateString()} - ${endOfWeek.toLocaleDateString()}`;
                                        chooseWeek.appendChild(option);

                                        currentDay.setDate(endOfWeek.getDate() + 1);
                                        weekNumber++;
                                    }

                                    const currentWeek = getCurrentWeek(year, month);
                                    const currentWeekLabel = `Week ${currentWeek}`;
                                    document.getElementById('chooseWeek').value = currentWeekLabel;
                                    loadWeekData(currentWeekLabel);
                                }

                                function getCurrentWeek(year, month) {
                                    const now = new Date();
                                    const startOfMonth = new Date(year, month, 1);
                                    const startOfWeek = new Date(startOfMonth);
                                    startOfWeek.setDate(startOfMonth.getDate() - startOfMonth.getDay()); // Start of the week (Sunday)

                                    const daysSinceStartOfWeek = Math.floor((now - startOfWeek) / (24 * 60 * 60 * 1000));
                                    const weekNumber = Math.ceil((daysSinceStartOfWeek + 1) / 7); // +1 for current week

                                    console.log(`Current Week Number: ${weekNumber}`); 

                                    return weekNumber;
                                }

                                function loadWeekData(weekLabel) {
                                    const year = document.getElementById('chooseYear').value;
                                    const month = document.getElementById('chooseMonth').value;

                                    //week number from the label
                                    const weekNumber = weekLabel.split(' ')[1];
                                    
                                    // Calculate the start and end dates of the week
                                    const startDate = new Date(year, month - 1, (weekNumber - 1) * 7 + 1);
                                    startDate.setDate(startDate.getDate() - startDate.getDay() + 1);
                                    const endDate = new Date(startDate);
                                    endDate.setDate(startDate.getDate() + 6); 

                                    // Format dates as YYYY-MM-DD
                                    const start = startDate.toISOString().split('T')[0];
                                    const end = endDate.toISOString().split('T')[0];

                                    console.log(`Loading data for: YearWeek ${year}${weekNumber} from ${start} to ${end}`);

                                    fetch(`/laundry_system/main/sales_report/sales_config/weeklyChart.php?start=${start}&end=${end}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            console.log('Chart Data:', data); 
                                            
                                            // Check if data is valid
                                            if (!data || !data.labels || !data.data) {
                                                console.error('Invalid data format');
                                                return;
                                            }
                                            
                                            const ctx = document.getElementById('weekchart').getContext('2d');
                                            
                                            // Destroy previous chart instance if it exists
                                            if (window.weekChartInstance) {
                                                window.weekChartInstance.destroy();
                                            }

                                            // Create the chart
                                            window.weekChartInstance = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: data.labels,
                                                    datasets: [{
                                                        label: 'Weekly Requests by Service and Category',
                                                        data: data.data,
                                                        backgroundColor: data.backgroundColors,
                                                        borderColor: data.borderColors,
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
                                                    plugins: {
                                                        legend: {
                                                            display: true,
                                                            position: 'bottom',
                                                            labels: {
                                                                color: '#000',
                                                                font: {
                                                                    size: 12
                                                                }
                                                            }
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Weekly Requests by Service and Category',
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
                                            renderWeekModalChart(data, weekLabel);
                                        })
                                        .catch(error => console.error('Error fetching chart data:', error));
                                }

                                function renderWeekModalChart(data, weekLabel) {
                                    const ctx = document.getElementById('weekChartModalCanvas').getContext('2d');

                                    if (window.weekChartModalInstance && typeof window.weekChartModalInstance.destroy === 'function') {
                                        window.weekChartModalInstance.destroy();
                                    }

                                    window.weekChartModalInstance = new Chart(ctx, {
                                        type: 'pie',
                                        data: {
                                            labels: data.labels,
                                            datasets: [{
                                                label: 'Weekly Requests by Service and Category',
                                                data: data.data,
                                                backgroundColor: data.backgroundColors,
                                                borderColor: data.borderColors,
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                title: {
                                                    display: true,
                                                    text: `Weekly Requests for ${weekLabel}`,
                                                    font: {
                                                        size: 14 
                                                    }
                                                },
                                                legend: {
                                                    display: true,
                                                    position: 'top',
                                                    labels: {
                                                        font: {
                                                            size: 12 
                                                        }
                                                    }
                                                }
                                            }
                                        },
                                        plugins: [ChartDataLabels]
                                    });
                                }

                                function downloadWeekChart() {
                                    const canvas = document.getElementById('weekChartModalCanvas');
                                    const link = document.createElement('a');
                                    link.href = canvas.toDataURL('image/png');  
                                    link.download = 'weekly-chart.png'; 
                                    link.click();
                                }

                            </script>

                        </div>

                        <!----------------------------------------ORDERS IN MONTH---------------------------------->
                        <div class="chart" id="monthlyChart">
                            <div class="chart-header">
                                 <h5>Service Requests <br> in Month</h5>
                                 <button id="Btn" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#monthModal">
                                    <i class='bx bx-menu'></i></button>
                            </div>
                           
                            <!--MONTH SELECTOR-->
                            <div class="selector">
                                <form method="POST" id="monthForm">
                                    <label for="monthSelect" class="visually-hidden">Select Month:</label>
                                    <select id="monthSelect" class="form-select" name="selectedMonth" 
                                        onchange="document.getElementById('monthForm').submit();">
                                        <?php
                                            $currentMonth = date("n"); 
                                            $selectedMonth = isset($_POST['selectedMonth']) ? $_POST['selectedMonth'] : $currentMonth;
                                            for ($month = 1; $month <= 12; $month++) {
                                                $monthName = date("F", mktime(0, 0, 0, $month, 1)); 
                                                $selected = $selectedMonth == $month ? 'selected' : '';
                                                echo "<option value='$month' $selected>$monthName</option>";
                                            }
                                        ?>
                                    </select>
                                </form>
                            </div>
                            <canvas id="monthchart"></canvas>

                            <!-- Modal -->
                            <div class="modal fade" id="monthModal" tabindex="-1" aria-labelledby="monthModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="monthModalLabel">Monthly Service Requests</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <canvas id="monthChartModalCanvas"></canvas>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="downloadMonthChart()">Download Chart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                //FOR MONTH SELECTOR
                                $selectedMonth = isset($_POST['selectedMonth']) ? $_POST['selectedMonth'] : date('n'); 
                                $currentYear = date("Y");

                                // Connect to database
                                $conn = new mysqli('localhost', 'root', '', 'laundry_db');

                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // query to fetch current month's orders by service option and category //
                                $query = "
                                    SELECT 
                                        laundry_service_option,
                                        laundry_category_option,
                                        COUNT(*) AS order_count
                                    FROM 
                                        service_request
                                    WHERE 
                                        MONTH(service_request_date) = $selectedMonth AND YEAR(service_request_date) = $currentYear
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
                                        renderModalMonthChart({
                                            labels: <?php echo json_encode($labels); ?>,
                                            data: <?php echo json_encode($data); ?>,
                                            backgroundColors: <?php echo json_encode($backgroundColors); ?>,
                                            borderColors: <?php echo json_encode($borderColors); ?>
                                        });
                                    });
                                    function renderModalMonthChart(data) {
                                        const ctx = document.getElementById('monthChartModalCanvas').getContext('2d');

                                        if (window.monthChartModal && typeof window.monthChartModal.destroy === 'function') {
                                            window.monthChartModal.destroy();
                                        }

                                        window.monthChartModal = new Chart(ctx, {
                                            type: 'doughnut',
                                            data: {
                                                labels: data.labels,
                                                datasets: [{
                                                    label: 'Orders in the Month',
                                                    data: data.data,
                                                    backgroundColor: data.backgroundColors,
                                                    borderColor: data.borderColors,
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
                                                        text: 'Monthly Requests',
                                                        font: {
                                                            size: 14
                                                        }
                                                    }
                                                }
                                            },
                                            plugins: [ChartDataLabels]
                                        });
                                    }

                                    function downloadMonthChart() {
                                        const canvas = document.getElementById('monthChartModalCanvas');
                                        const link = document.createElement('a');
                                        link.href = canvas.toDataURL('image/png');
                                        link.download = 'monthly-chart.png';
                                        link.click();
                                    }
                                
                            </script>
                        </div>

                        <!--------------------------------YEAR CHART----------------------------->
                        <div class="chart" id="yearlyChart" >
                            <div class="chart-header">
                                <h5>Service Requests <br> in Year</h4>
                                <button id="Btn" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#yearModal">
                                    <i class='bx bx-menu'></i></button>
                            </div>
                            
                            <!--YEAR SELECTOR-->
                            <div class="selector">
                                <form method="POST" id="yearForm">
                                    <label for="yearSelect" class="visually-hidden">Select Year:</label>
                                    <select id="yearSelect" name="selectedYear"
                                    class="form-select" onchange="document.getElementById('yearForm').submit();">
                                        <?php
                                            $currentYear = date("Y");
                                            for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                                                $selected = isset($_POST['selectedYear']) && $_POST['selectedYear'] == $year ? 'selected' : '';
                                                echo "<option value='$year' $selected>$year</option>";
                                            }
                                        ?>
                                    </select>
                                </form>
                            </div>

                            <canvas id="yearchart"></canvas>

                            <!--MODAL-->
                            <div class="modal fade" id="yearModal" tabindex="-1" aria-labelledby="yearModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="yearModalLabel">Yearly Service Requests</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <canvas id="yearChartModalCanvas"></canvas>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="downloadYearChart()">Download Chart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                //YEAR SELECTOR
                                $selectedYear = isset($_POST['selectedYear']) ? $_POST['selectedYear'] : $currentYear;

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
                                        YEAR(service_request_date) = '$selectedYear'
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

                                function renderModalYearChart(data) {
                                    const ctxModal = document.getElementById('yearChartModalCanvas').getContext('2d');
                                    if (window.yearChartModal && typeof window.yearChartModal.destroy === 'function') {
                                        window.yearChartModal.destroy();
                                    }
                                    window.yearChartModal = new Chart(ctxModal, {
                                        type: 'line',
                                        data: {
                                            labels: data.labels,
                                            datasets: [{
                                                label: 'Orders in Year',
                                                data: data.data,
                                                backgroundColor: data.backgroundColors,
                                                borderColor: data.borderColors,
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: { position: 'bottom' },
                                                title: { display: true, text: 'Yearly Requests' }
                                            },
                                            scales: {
                                                y: {beginAtZero: true}
                                            }
                                        }
                                    });
                                }

                                function downloadYearChart() {
                                    const canvas = document.getElementById('yearChartModalCanvas');
                                    const link = document.createElement('a');
                                    link.href = canvas.toDataURL('image/png');
                                    link.download = 'yearly-chart.png';
                                    link.click();
                                }

                                renderModalYearChart({
                                    labels: <?php echo json_encode($labels); ?>,
                                    data: <?php echo json_encode($data); ?>,
                                    backgroundColors: <?php echo json_encode($backgroundColors); ?>,
                                    borderColors: <?php echo json_encode($borderColors); ?>
                                });
                            </script>
                        </div>

                    </div> <!--end of charts-->   
                </div> <!--end of charts-container-->

            <!---------------------------TRANSACTION SUMMARY-------------------------------->
            <div class="table-container">
                <h3>Transaction Summary</h3>
                <div class="btns">
                    <button class="btn btn-secondary" data-filter="daily" onclick="highlightButton(this)">
                        <img src="/laundry_system/main/icons/calendar-regular-24.png" alt="Calendar Icon">    
                        Daily
                    </button>
                    <button class="btn btn-secondary" data-filter="weekly" onclick="highlightButton(this)">
                        <img src="/laundry_system/main/icons/calendar-regular-24.png" alt="Calendar Icon">    
                        Weekly
                    </button>
                    <button class="btn btn-secondary" data-filter="monthly" onclick="highlightButton(this)">
                        <img src="/laundry_system/main/icons/calendar-regular-24.png" alt="Calendar Icon">    
                        Monthly
                    </button>
                    <button class="btn btn-secondary" data-filter="yearly" onclick="highlightButton(this)">
                        <img src="/laundry_system/main/icons/calendar-regular-24.png" alt="Calendar Icon">
                        Yearly
                    </button>
                    <button class="btn btn-info" onclick="printTransactionTable()">
                        <img src="/laundry_system/main/icons/printer-regular-24.png" alt="Printer Icon">
                        Print
                    </button>
                </div>

                <div class="search_bar m-1">
                    <input class="form-control" type="text" id="filter_transac" placeholder="Search transactions...">
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover" id="table_summary">
                        <thead>
                            <tr>
                                <th scope="col">Transaction ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">Order/Session ID</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Service</th>
                                <th scope="col">Category</th>
                                <th scope="col">Rate</th>
                                <th scope="col">Weight</th>
                                <th scope="col">Service Type</th>
                                <th scope="col">Laundry Cycle</th>
                                <th scope="col">Service Fee</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider" id="transaction-table-body"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10" style="text-align: right;"><strong>Total Revenue:</strong></td>
                                <td colspan="2"><strong>₱<span id="total-revenue">0.00</span></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!--PAGINATION LINK-->
                    </ul>
                </nav>

                <!-- JavaScript -->
                <script>
                    window.onload = function() {
                        fetchTransactionSummary('all'); // Fetch all transactions by default
                    };

                    function highlightButton(button) {
                        var buttons = document.querySelectorAll('.btn-secondary');
                        buttons.forEach(function(btn) {
                            btn.classList.remove('clicked');
                        });
                        button.classList.add('clicked');
                    }

                    // Filter table by day, week, month, year
                    document.querySelectorAll('.btns button').forEach(button => {
                        button.addEventListener('click', function() {
                            const filter = this.getAttribute('data-filter');
                            fetchTransactionSummary(filter);
                        });
                    });

                    function fetchTransactionSummary(filter, page = 1) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '/laundry_system/main/sales_report/sales_config/transaction_summary.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (this.status === 200) {
                                const response = JSON.parse(this.responseText);

                                // Update transaction table and total revenue
                                document.getElementById('transaction-table-body').innerHTML = response.table_data;
                                document.getElementById('total-revenue').innerText = response.total_revenue;

                                // Update pagination
                                let pagination = '';
                                if (response.page > 1) {
                                    pagination += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="fetchTransactionSummary('${filter}', ${response.page - 1})">&laquo;</a></li>`;
                                }

                                for (let i = 1; i <= response.total_pages; i++) {
                                    pagination += `<li class="page-item ${i === response.page ? 'active' : ''}"><a class="page-link" href="javascript:void(0)" onclick="fetchTransactionSummary('${filter}', ${i})">${i}</a></li>`;
                                }

                                if (response.page < response.total_pages) {
                                    pagination += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="fetchTransactionSummary('${filter}', ${response.page + 1})">&raquo;</a></li>`;
                                }

                                document.getElementById('pagination').innerHTML = pagination;
                            }
                        };

                        // Send filter and page data
                        xhr.send('filter=' + filter + '&page=' + page);
                    }

                    //print table
                    function printTransactionTable() {
                        // Get the current filtered table data
                        const filter = document.querySelector('.btns button.clicked') ? document.querySelector('.btns button.clicked').getAttribute('data-filter') : 'all';
                        
                        //AJAX request to fetch the filtered table data for printing
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '/laundry_system/main/sales_report/sales_config/transaction_summary.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        
                        xhr.onload = function() {
                            if (this.status === 200) {
                                const response = JSON.parse(this.responseText);
                                
                                // table content with the returned data
                                var tableContent = `
                                    <table id="table_summary">
                                        <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Request Date</th>
                                                <th>Customer Order ID</th>
                                                <th>Customer Name</th>
                                                <th>Laundry Service Option</th>
                                                <th>Laundry Category Option</th>
                                                <th>Price</th>
                                                <th>Weight</th>
                                                <th>Service Option</th>
                                                <th>Laundry Cycle</th>
                                                <th>Service Fee</th>
                                                <th>Total Amount</th>
                                                <th>Order Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${response.table_data}
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="10" style="text-align: right;"><strong>Total Revenue:</strong></td>
                                                <td colspan="2"><strong>₱${response.total_revenue}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>`;
                                
                                // Open a new window for printing
                                var newWindow = window.open('', '', 'height=600,width=800');
                                newWindow.document.write('<html><head><title>Transaction_Summary</title>');
                                newWindow.document.write('<style>' +
                                    'table {' + 
                                    'width: 95%;' + 
                                    'border-collapse: collapse; margin: 20px auto;}' + 
                                    'th, td { border: 1px solid black; padding: 5px; text-align: left; }' + 
                                    '@media print { ' +
                                    '#table_summary { width: 95%; border-collapse: collapse; margin: 0 auto;}' + 
                                    'th, td { font-size: 11px; padding: 5px; }' + 
                                    '.table-responsive { margin: 0 auto; padding: 5px 5px; }' +
                                    '}' + 
                                    '.header { font-size: 16px; font-weight: bold; text-align: center; margin-top: 20px; }' +
                                    '</style>');
                                newWindow.document.write('</head><body>');
                                newWindow.document.write('<h2 class="header">Transaction Summary</h2>');
                                newWindow.document.write(tableContent);
                                newWindow.document.write('</body></html>');
                                newWindow.document.close();
                                
                                // Trigger the print
                                newWindow.print();
                            }
                        };
                        
                        // Send the filter to get the table data for the selected filter
                        xhr.send('filter=' + filter + '&print_all=true');
                    }

                    //dynamic search bar
 $(document).ready(function(){
                        $("#filter_transac").on("keyup", function() {
                            var value = $(this).val().toLowerCase();
                            $("#transaction-table-body tr").filter(function() {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                            });
                        });
                    });
                </script>
            </div> <!---END OF TABLE CONTAINER-->
            
        </div> <!---end of main-->
    </div> <!--end of wrapper---->
</body>
    
    <!--JAVASCRIPT-->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="report.js"></script>

</html>