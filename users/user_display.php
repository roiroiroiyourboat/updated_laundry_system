<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laundry_db";

$conn = new mysqli($servername, $username, $password, $dname);

if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

$sql = "SELECT user_id, username, first_name, last_name, user_role, last_active, user_status FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<td> 
                <td>" . $row["user_id"]. "</td>
                <td>" . $row["username"]. "</td>
                <td>" . $row["first_name"]. "</td>
                <td>" . $row["last_name"]. "</td>
                <td>" . $row["user_role"]. "</td>
                <td>" . $row["last_active"]. "</td>
                <td>" . $row["user_status"]. "</td>
                <td>" . $row["date_created"]. "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No results found</td></tr>";
}

$conn->close();
?>