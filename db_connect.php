<?php
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "event_db";

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
