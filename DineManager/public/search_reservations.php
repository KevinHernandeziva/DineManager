<?php
include('../includes/db_connection.php');

$search_term = '';
$reservation_details = null;
$error_message = null;

if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search']);

    $search_by_id = is_numeric($search_term);

    if ($search_by_id) {
        $query = "SELECT r.reservation_id, r.customer_name, r.reservation_date, r.reservation_time, r.num_guests, r.special_requests, c.contact_info
                  FROM reservations r
                  LEFT JOIN customers c ON r.customer_id = c.customer_id
                  WHERE r.reservation_id = $search_term OR r.customer_id = $search_term";
    } else {
        $query = "SELECT r.reservation_id, r.customer_name, r.reservation_date, r.reservation_time, r.num_guests, r.special_requests, c.contact_info
                  FROM reservations r
                  LEFT JOIN customers c ON r.customer_id = c.customer_id
                  WHERE r.customer_name LIKE '%$search_term%'";
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $reservation_details = mysqli_fetch_assoc($result);
    } else {
        $error_message = "No reservation found for the provided ID or customer name.";
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    $delete_query = "DELETE FROM reservations WHERE reservation_id = $delete_id";
    
    if (mysqli_query($conn, $delete_query)) {
        header("Location: search_reservations.php");
        exit();
    } else {
        echo "Error deleting reservation: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Reservations - DineManager</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<header>
    <h1>Search Reservations</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="add_reservation.php">Add Reservation</a></li>
        <li><a href="view_reservations.php">View Reservations</a></li>
        <li><a href="search_reservations.php">Search Reservations</a></li>
        <li><a href="customer_preferences.php">Update Special Requests</a></li>
        <li><a href="dining_preferences.php">Dining Preferences</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Search Reservations</h2>
    
    <form method="POST" action="search_reservations.php">
        <input type="text" name="search" placeholder="Search by Reservation ID, Customer ID, or Customer Name" value="<?php echo htmlspecialchars($search_term); ?>" />
        <button type="submit">Search</button>
    </form>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

    <?php if ($reservation_details): ?>
        <h3>Reservation Details</h3>
        <table>
            <tr>
                <th>Reservation ID</th>
                <td><?php echo htmlspecialchars($reservation_details['reservation_id']); ?></td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td><?php echo htmlspecialchars($reservation_details['customer_name']); ?></td>
            </tr>
            <tr>
                <th>Contact Info</th>
                <td><?php echo htmlspecialchars($reservation_details['contact_info']); ?></td>
            </tr>
            <tr>
                <th>Reservation Date</th>
                <td><?php echo htmlspecialchars($reservation_details['reservation_date']); ?></td>
            </tr>
            <tr>
                <th>Reservation Time</th>
                <td><?php echo htmlspecialchars($reservation_details['reservation_time']); ?></td>
            </tr>
            <tr>
                <th>Number of Guests</th>
                <td><?php echo htmlspecialchars($reservation_details['num_guests']); ?></td>
            </tr>
            <tr>
                <th>Special Requests</th>
                <td><?php echo htmlspecialchars($reservation_details['special_requests']); ?></td>
            </tr>
        </table>
        <br><br>
        <form action="search_reservations.php" method="GET" onsubmit="return confirm('Are you sure you want to delete this reservation?');">
            <input type="hidden" name="delete_id" value="<?php echo $reservation_details['reservation_id']; ?>">
            <button type="submit" class="btn">Delete Reservation</button>
        </form>
    
    <?php endif; ?>
</div>

<footer>
    <p>&copy; DineManager - Restaurant Reservation System</p>
</footer>

</body>
</html>

<?php
mysqli_close($conn);
?>
