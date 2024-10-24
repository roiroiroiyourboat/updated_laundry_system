<?php
require_once('users_db.php');

// $search = "";
// if (isset($_POST['search'])) {
//     $search = $con->real_escape_string($_POST['search']);
//     $query = "SELECT * FROM users WHERE username LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%'";
// } else {
//     $query = "SELECT * FROM users";
// }

$result = mysqli_query($con, $query);

if (!$result) {
    die("Query error: " . mysqli_error($con));
}

// Generate table rows
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['user_role']) . "</td>";
    echo "<td>" . htmlspecialchars($row['last_active']) . "</td>";
    echo "<td>" . htmlspecialchars($row['user_status']) . "</td>";
    echo "<td>" . htmlspecialchars($row['date_created']) . "</td>";
    echo "</tr>";
}

$con->close();
?>