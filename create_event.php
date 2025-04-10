<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $created_by = $_SESSION['user_id'];
    $edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;

    if ($edit_id) {
        $query = "UPDATE events SET title='$title', description='$description', 
                  event_date='$event_date', event_time='$event_time', location='$location' 
                  WHERE id=$edit_id";
    } else {
        $query = "INSERT INTO events (title, description, event_date, event_time, location, created_by) 
                  VALUES ('$title', '$description', '$event_date', '$event_time', '$location', $created_by)";
    }
    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM events WHERE id=$delete_id");
    header("Location: dashboard.php");
    exit();
}

$edit_event = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $result = mysqli_query($conn, "SELECT * FROM events WHERE id=$edit_id");
    $edit_event = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create/Edit Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $edit_event ? 'Edit Event' : 'Create New Event'; ?></h1>
        <form method="POST">
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo $edit_event ? $edit_event['title'] : ''; ?>" required><br>
            <label>Description:</label>
            <textarea name="description"><?php echo $edit_event ? $edit_event['description'] : ''; ?></textarea><br>
            <label>Date:</label>
            <input type="date" name="event_date" value="<?php echo $edit_event ? $edit_event['event_date'] : ''; ?>" required><br>
            <label>Time:</label>
            <input type="time" name="event_time" value="<?php echo $edit_event ? $edit_event['event_time'] : ''; ?>" required><br>
            <label>Location:</label>
            <input type="text" name="location" value="<?php echo $edit_event ? $edit_event['location'] : ''; ?>" required><br>
            <?php if ($edit_event): ?>
                <input type="hidden" name="edit_id" value="<?php echo $edit_event['id']; ?>">
            <?php endif; ?>
            <button type="submit"><?php echo $edit_event ? 'Update' : 'Create'; ?> Event</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
