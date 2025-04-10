<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'admin') {
    header("Location: index.php");
    exit();
}

$event_id = (int)$_GET['event_id'];
$user_id = $_SESSION['user_id'];

// Check if already registered
$check_query = "SELECT * FROM registrations WHERE user_id=$user_id AND event_id=$event_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    $query = "INSERT INTO registrations (user_id, event_id) VALUES ($user_id, $event_id)";
    mysqli_query($conn, $query);
}
header("Location: dashboard.php");
exit();
?>
