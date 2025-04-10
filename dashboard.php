<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Fetch events
$events_query = "SELECT e.*, u.username 
                 FROM events e 
                 LEFT JOIN users u ON e.created_by = u.id 
                 WHERE e.event_date >= CURDATE()";
$events_result = mysqli_query($conn, $events_query);

// Fetch registered events for the user
$registered_query = "SELECT e.title, e.event_date, e.event_time, e.location 
                     FROM registrations r 
                     JOIN events e ON r.event_id = e.id 
                     WHERE r.user_id = $user_id";
$registered_result = mysqli_query($conn, $registered_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Event Management System</h1>
        <p>Welcome, <?php echo ($role == 'admin') ? 'Admin' : 'User'; ?>! <a href="logout.php">Logout</a></p>

        <?php if ($role == 'admin'): ?>
            <h2>Create New Event</h2>
            <a href="create_event.php">Create Event</a>
        <?php endif; ?>

        <h2>Upcoming Events</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Created By</th>
                <th>Action</th>
            </tr>
            <?php while ($event = mysqli_fetch_assoc($events_result)): ?>
                <tr>
                    <td><?php echo $event['title']; ?></td>
                    <td><?php echo $event['event_date']; ?></td>
                    <td><?php echo $event['event_time']; ?></td>
                    <td><?php echo $event['location']; ?></td>
                    <td><?php echo $event['username']; ?></td>
                    <td>
                        <?php if ($role != 'admin'): ?>
                            <a href="register_event.php?event_id=<?php echo $event['id']; ?>">Register</a>
                        <?php elseif ($role == 'admin'): ?>
                            <a href="create_event.php?edit_id=<?php echo $event['id']; ?>">Edit</a> |
                            <a href="create_event.php?delete_id=<?php echo $event['id']; ?>" 
                               onclick="return confirm('Are you sure?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <?php if ($role != 'admin'): ?>
            <h2>Your Registered Events</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                </tr>
                <?php while ($registered = mysqli_fetch_assoc($registered_result)): ?>
                    <tr>
                        <td><?php echo $registered['title']; ?></td>
                        <td><?php echo $registered['event_date']; ?></td>
                        <td><?php echo $registered['event_time']; ?></td>
                        <td><?php echo $registered['location']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
