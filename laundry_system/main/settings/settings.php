<?php
session_start(); 

$user_role = $_SESSION['user_role'];

if(!isset($_SESSION['user_role'])) {
    header('location: /laundry_system/main/homepage/homepage.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'laundry_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve current settings from the database
$sql = "SELECT min_kilos, delivery_day FROM settings";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$sqlServiceOption = "SELECT price FROM service_option_price WHERE service_option_type = 'Delivery'";
$resultDelivery = mysqli_query($conn, $sqlServiceOption);
$rowDelivery = mysqli_fetch_assoc($resultDelivery);
$delivery_charge = $rowDelivery['price'];

$sqlServiceOption = "SELECT price FROM service_option_price WHERE service_option_type = 'Rush'";
$resultPickup = mysqli_query($conn, $sqlServiceOption);
$rowPickup = mysqli_fetch_assoc($resultPickup);
$rush_charge = $rowPickup['price'];

$minimum_kilos = $row['min_kilos'];
$delivery_day = $row['delivery_day'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $minimum_kilos = $_POST['min_kilos'];
    $delivery_day = $_POST['delivery_day'];
    $delivery_charge = $_POST['delivery_charge'];
    $rush_charge = $_POST['rush_charge'];

    $success = true;
    $errors = "";

    //to update min kilos and delivery period
    $sql = "UPDATE settings SET min_kilos='$minimum_kilos', delivery_day='$delivery_day'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        $success = false;
        $errors .= "Error updating settings: " . mysqli_error($conn) . "\n";
    }

    //to update delivery fee
    $sql = "UPDATE service_option_price SET price='$delivery_charge' WHERE service_option_type='Delivery'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        $success = false;
        $errors .= "Error updating delivery charge: " . mysqli_error($conn) . "\n";
    }

    //to update rush fee
    $sql = "UPDATE service_option_price SET price='$rush_charge' WHERE service_option_type='Rush'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        $success = false;
        $errors .= "Error updating rush charge: " . mysqli_error($conn) . "\n";
    }

    // Return a JSON response to the frontend
    if ($success) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Settings updated successfully!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => $errors
        ]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="settings.css">
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
                    <h2>Settings</h2>
                </div>
            </nav>

            <div class="buttons">
                <div class="wdf_button">
                    <a href="/laundry_system/main/settings/categ1/categ1.php" class="button" id="wdfBtn">Wash/Dry/Fold</a>
                </div>
                    
                <div class="wdp_button">
                    <a href="/laundry_system/main/settings/categ2/categ2.php" class="button" id="wdpBtn">Wash/Dry/Press</a>
                </div>
                
                <div class="dry_button">
                    <a href="/laundry_system/main/settings/categ3/categ3.php" class="button" id="dryBtn">Dry only</a>
                </div>       
            </div> 

            <div class="form-settings" id="mainForm">
                <form class="form-container" id="settingsForm" method="POST">
                    <label for="min_kilos"><b>Minimum Kilos:</b></label>
                    <input type="number" class="form-control" id="min_kilos" name="min_kilos" value="<?php echo $minimum_kilos ?>">

                    <label for="delivery_date"><b>Delivery Period:</b></label>
                    <input type="number" class="form-control" id="delivery_day" name="delivery_day" value="<?php echo $delivery_day ?>">

                    <label for="delivery_charge"><b>Delivery Fee: </b></label>
                    <input type="number" class="form-control" id="delivery_charge" name="delivery_charge" value="<?php echo $delivery_charge ?>">
                    
                    <label for="rush_charge"><b>Rush Fee:</b></label>
                    <input type="number" class="form-control" id="rush_charge" name="rush_charge" value="<?php echo $rush_charge ?>">

                    <button type="submit" class="btn btn-success" id="submit_btn" name="submit">Submit</button>
                </form>
            </div>

            <script>
                const form = document.getElementById('settingsForm');

                //form submission
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); //prevent the default form submission behavior

                    //collect form data
                    const formData = new FormData(form);

                    //send form data using AJAX request
                    fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while submitting the form.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                });
            </script>


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

         </div> <!--end of main on. -->
    </div>

</body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="settings.js"></script>

</html>