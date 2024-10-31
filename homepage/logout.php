<?php
session_start();

if (session_destroy()) {
    header("Location: /laundry_system/main/homepage/homepage.php");
    exit();
}
?>