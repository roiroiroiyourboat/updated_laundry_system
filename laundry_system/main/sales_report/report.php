<?php
session_start(); 

$user_role = $_SESSION['user_role'];

if(!isset($_SESSION['user_role'])) {
    header('location: /laundry_system/main/homepage/homepage.php');
    exit();
}
?>

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

                <?php if ($user_role === 'admin') : ?>
                    <li class="sidebar-item">
                        <a href="/laundry_system/main/users/users.php" class="sidebar-link">
                            <i class="lni lni-users"></i>
                            <span>Users</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="/laundry_system/main/records/records.php" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
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
                <?php endif; ?>

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

                    <?php if ($user_role === 'admin') : ?>
                    <li class="sidebar-item">
                        <a href="/laundry_system/main/settings/settings.php" class="sidebar-link">
                            <i class="lni lni-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>

                    <hr style="border: 1px solid #b8c1ec; margin: 8px">

                    <li class="sidebar-item">
                        <a href="/laundry_system/main/archived/archive_users.php" class="sidebar-link">
                            <i class='bx bxs-archive-in'></i>
                            <span class="nav-item">Archived</span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>

            <div class="sidebar-footer">
                <a href="#" id="btn_logout" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
        <!-------------MAIN CONTENT------------->
        <div class="main-content">
            <nav>
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Sales Report</h2>
                </div>
            </nav>

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
                           
                            <input type="date" class="form-control" id="chooseDate" value="">
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
                                            <button type="button" class="btn btn-primary" id="dl_daily_chart">Download Chart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-------------------------------------ORDERS IN WEEK---------------------------------------->          
                        <div class="chart" id="weeklyChart">
                            <div class="chart-header">
                                <h5>Service Requests <br> in Week</h5>
                                <button id="Btn" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#weekModal">
                                <i class='bx bx-menu'></i></button>
                            </div>
                        
                                <div class="selector">
                                    <!---SELECT YEAR--->
                                    <select id="chooseYear" class="form-select">
                                    </select>

                                    <!---SELECT MONTH--->
                                    <select id="chooseMonth" class="form-select">
                                    </select>

                                    <!---SELECT WEEK--->
                                    <select id="chooseWeek" class="form-select">
                                    </select>
                                </div>
                            <canvas id="weekchart"></canvas>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="weekModal" tabindex="-1" aria-labelledby="weekModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="weekModalLabel">Weekly Service Requests</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <canvas id="weekChartModalCanvas"></canvas>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="dl_weekly_chart">Download Chart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <select id="monthSelect" class="form-select" name="selectedMonth">
                                        <!--options-->
                                    </select>
                                </form>
                            </div>
                            <canvas id="monthchart"></canvas>

                            <!-- Modal -->
                            <div class="modal fade" id="monthModal" tabindex="-1" aria-labelledby="monthModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="monthModalLabel">Monthly Service Requests</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <canvas id="monthChartModalCanvas"></canvas>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="dl_monthly_chart">Download Chart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <select id="yearSelect" name="selectedYear" class="form-select">
                                        <!--options here-->
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
                                            <button type="button" class="btn btn-primary" id="dl_yearly_chart">Download Chart</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>                
                    
                    </div> <!--end of charts-->   
                </div> <!--end of charts-container-->

            <!---------------------------TRANSACTION SUMMARY-------------------------------->
            <div class="table-container">
                <h3>Transaction Summary</h3>
                <div class="btns">
                    <button class="btn btn-primary" data-filter="daily" id="btnDaily">
                        <img src="/laundry_system/main/icons/calendar-regular-24.png" alt="Calendar Icon">    
                        Daily
                    </button>
                    <button class="btn btn-primary" data-filter="weekly" id="btnWeekly">
                        <img src="/laundry_system/main/icons/calendar-regular-24.png" alt="Calendar Icon">    
                        Weekly
                    </button>
                    <button class="btn btn-primary" data-filter="monthly" id="btnMonthly">
                        <img src="/laundry_system/main/icons/calendar-regular-24.png" alt="Calendar Icon">    
                        Monthly
                    </button>
                    <button class="btn btn-primary" data-filter="yearly" id="btnYearly">
                        <img src="/laundry_system/main/icons/calendar-regular-24.png" alt="Calendar Icon">
                        Yearly
                    </button>
                    <button class="btn btn-info" id="btnPrint">
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
                                <th scope="col">Session ID</th>
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
                                <td colspan="11" style="text-align: right;"><strong>Total Revenue:</strong></td>
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
            </div> <!---END OF TABLE CONTAINER-->
            
            <div id="logoutModal" class="modal" style="display:none;">
                <div class="modal-cont">
                    <span class="close">&times;</span>
                    <h2>Do you want to logout?</h2>
                    <div class="modal-buttons">
                        <a href="/laundry_system/main/homepage/logout.php" class="btn btn-yes">Yes</a>
                        <button class="btn btn-no">No</button>
                    </div>
                </div>
            </div>

        </div> <!---end of main-->
    </div> <!--end of wrapper---->
</body>
    
    <!--JAVASCRIPT-->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="report.js"></script>

</html>