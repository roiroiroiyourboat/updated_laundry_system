<?php

$conn = new mysqli('localhost', 'root','','db_laundry');

    if(!$conn){
        die(mysqli_error($conn));
    }
    //echo "connected";

?>