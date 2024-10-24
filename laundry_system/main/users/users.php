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
    <title>Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="users.css">
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
                    <h2>Users</h2>

                    <div class="search_bar" m-1>
                        <input class="form-control" type="text" id="filter_user" placeholder="Search users...">
                    </div>
                </div>   
            </nav>     

            <div class="box">
                <form action="users.php" method="POST">
                        <div class="add_button">
                            <button type="button" class="btn btn-success" id="addUserButton"> 
                            <i class='bx bxs-user-plus'></i>Add User</button>
                        </div>    
                </form>  
            </div>    

            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-dark text-white">                             
                            <th>User ID</th>
                            <th>Username</th>                       
                            <th>First name</th>
                            <th>Last name</th>
                            <th>User Role</th>
                            <th>Last Active</th>
                            <th>User Status</th>
                            <th>Date Created</th>
                            <th>Edit</th>
                            <th>Archive</th>
                        </tr>
                    </thead>
                    <tbody id = "user_table">
                        <?php 
                            $con = new mysqli('localhost', 'root', '', 'laundry_db');
                            
                            if($con->connect_error) {
                                die("Connection Error: " . $con->connect_error);
                            }

                        // Fetch all users without any filtering
                        $query = "SELECT * FROM users"; 
                        $result = mysqli_query($con, $query);

                        if ($result->num_rows > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $current_time = new DateTime();
                                $last_active_time = new DateTime($row['last_active']);
                                $interval = $current_time->diff($last_active_time);
                                $user_status = ($interval->days < 30) ? 'Active' : 'Inactive';
                        ?>
                                <tr>
                                    <td><?php echo $row['user_id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['first_name']; ?></td>
                                    <td><?php echo $row['last_name']; ?></td>
                                    <td><?php echo $row['user_role']; ?></td>
                                    <td><?php echo $row['last_active']; ?></td>
                                    <td><?php echo $user_status; ?></td>
                                    <td><?php echo $row['date_created']; ?></td>
                                    <td>
                                        <a href="javascript:void(0);" class="edit-btn" data-id="<?php echo $row['user_id']; ?>" data-username="<?php echo $row['username']; ?>" 
                                            data-firstname="<?php echo $row['first_name']; ?>" data-lastname="<?php echo $row['last_name']; ?>" 
                                            data-userrole="<?php echo $row['user_role']; ?>">
                                            <i class="bx bxs-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="archive-btn" data-id="<?php echo $row['user_id']; ?>">
                                            <i class='bx bxs-archive-in'></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="10">No results found</td>
                            </tr>
                        <?php
                        }
                        ?>      
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination">
                    <!--PAGINATION LINK-->
                </ul>
            </nav>

            <div class="modal" id="addUserModal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1>Add Users</h1>
                        <span class="close">&times;</span>
                    </div>    

                    <div class="modal-body">
                        <?php
                        if (isset($error_message)) {
                            echo "<div class='alert alert-danger'>$error_message</div>";
                        }
                        ?>
                        <form method="POST" action="add_users.php" id="userForm">  
                            <div class="row">
                                <div class="col">
                                    <label for="first_name">First name</label>
                                    <input type="text" class="form-control" id="first_name" placeholder="Enter first name" name="fname" autocomplete="given-name" required>   
                                </div>

                                <div class="col">
                                    <label for="last_name">Last name</label>
                                    <input type="text" class="form-control" id="last_name" placeholder="Enter last name" name="lname" autocomplete="family-name" required>
                                </div>
                            </div> 

                            <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" autocomplete="username" required>
                            </div> 

                            <div class="form-group">
                                    <label for="user_role">User Role</label>
                                    <select class="form-control" name="user_role" id="user_role" autocomplete="role" required>
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                    </select>      
                            </div>    

                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="password-container">
                                  <input type="password" class="form-control" placeholder="Create password" name="password" id="password" autocomplete="new-password" required>
                                 <!-- <span class="toggle-password" onclick="togglePassword('password')">&#128065;</span> -->
                                </div>
                                <div id="passwordHelp" class="alert alert-danger mt-2" style="display:none">
                                <ul>
                                    <li id="length" style="color:red;">At least 8 characters</li>
                                    <li id="uppercase" style="color:red;">At least two uppercase letter</li>
                                    <li id="lowercase" style="color:red;">At least two lowercase letter</li>
                                    <li id="number" style="color:red;">At least four number</li>
                                </ul>
                                </div>
                            </div>

                            <!--<div class="form-group">
                                <label>Confirm Password</label>
                                <div class="password-container">
                                    <input type="password" class="form-control" placeholder="Confirm password" name="confirm_password" required>
                                    <span class="toggle-password" onclick="togglePassword('confirm_password')">&#128065;</span>
                                </div>
                                <small id="confirmPasswordHelp" class="form-text text-muted">Passwords do not match.</small>
                            </div>  -->

                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="button" class="btn btn-info">Clear</button>
                        </form>
                    </div>  
                </div>    
            </div>   <!-- closing tag add user modal body --> 
            
            <!-- edit modal structure -->
            <div class="modal" id="editModal" style="display: none;">
                <div class="modal-content"> <!--modal-content -->
                    <div class="modal-header">
                        <h1>Edit User</h1>
                        <span class="close-btn">&times;</span>
                    </div>

                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="editUserId" name="user_id">

                            <div class="row">
                                <div class="col">
                                    <label for="editFirstName">First Name</label>
                                    <input type="text" class="form-control" id="editFirstName" name="first_name" autocomplete="given-name">
                                </div>

                                <div class="col">
                                    <label for="editLastName">Last Name</label>
                                    <input type="text" class="form-control" id="editLastName" name="last_name" autocomplete="family-name"> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="editUsername">Username</label>
                                <input type="text" class="form-control" id="editUsername" name="username" autocomplete="username">
                            </div>
                            
                            <div class="form-group">
                                <label for="editUserRole">User Role</label>
                                <input type="text" class="form-control" id="editUserRole" name="user_role" autocomplete="role">
                            </div>
                        
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                    </div> <!-- modal body -->
                </div> <!-- modal edit content -->
            </div> <!--closing tag for the edit modal structure-->

            <!-- for Archive --> 
            <div class="Archvmodal" id="archiveModal">
                <div class="modal-cnt">
                    <span class="close" id="closeArchiveModal">&times;</span>
                    <p>Do you want to archive this user?</p>
                    <button type="button" id="confirmArchiveButton" class="btn btn-success">Yes</button>
                   <!-- <button id="cancelArchiveButton" class="btn btn-danger">No</button> -->
                    <button type="button" id="cancelArchiveButton" class="btn btn-danger">No</button>
                </div>
            </div>

            <div class="Archvmodal" id="successModal">
                <div class="modal-cnt">
                    <span class="close" id="closeSuccessModal">&times;</span>
                    <p>You have successfully archived this user's details.</p>
                    <button id="closeSuccessButton" class="btn btn-primary">OK</button>
                </div>
            </div>

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

        </div> <!-- main content -->
    </div>
</body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="users.js"></script>

</html>