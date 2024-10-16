<?php
$conn = new mysqli('localhost', 'root', '', 'laundry_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//fetch the rows for wash/dry/fold
$sql = "SELECT scp.price, c.laundry_category_option, s.laundry_service_option
        FROM service_category_price scp
        JOIN category c ON scp.category_id = c.category_id
        JOIN service s ON scp.service_id = s.service_id
        WHERE s.service_id = 1";

$result = $conn->query($sql); 

$prices = [];
if ($result) {
    while ($row = $result->fetch_assoc()) { 
        $prices[] = $row; 
    }
} else {
    die("Query Failed: " . $conn->error); 
}

//close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="categ1.css">
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
        <div class="main-content">
            <nav>
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Settings</h2>
                </div>

                <div class="text" style="text-align: center;" name="category">
                    <h2>Wash/Dry/Fold</h2>
                </div>
            </nav>

            <div class="box">
                    <form action="edit1.php" method="POST">
                        <button type="submit" class="btn btn-success" id="updatePriceButton">
                        <i class='bx bxs-user-plus'></i>Update Price</button>
                    </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>Category Option</th>
                            <th>Service Option</th>
                            <th>Prices</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($prices as $row) {
                            $laundry_category_option = $row['laundry_category_option'];
                            $laundry_service_option = $row['laundry_service_option'];
                            $price = $row['price'];
                                        
                            echo '<tr>
                                  <th scope="row">' . $laundry_category_option . '</th>
                                  <td>' . $laundry_service_option . '</td>
                                  <td>' . $price . '</td>
                                  </tr>'; 
                                }
                            ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="categ1.js"></script>

</html>