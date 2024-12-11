<?php
include('../includes/db_connection.php');

$query = "SELECT reservation_id, customer_id, customer_name, reservation_date, reservation_time, num_guests FROM reservations";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching reservations: " . mysqli_error($conn));
}

$reservations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reservations[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations - DineManager</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<header>
    <h1>View All Reservations</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="add_reservation.php">Add Reservation</a></li>
        <li><a href="view_reservations.php">View Reservations</a></li>
        <li><a href="search_reservations.php">Search Reservations</a></li>
        <li><a href="customer_preferences.php">Update Special Requests</a></li>
    </ul>
</nav>

<div class="container">
    <h2>View All Reservations</h2>

    <?php if (count($reservations) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Customer ID</th> 
                    <th>Customer Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Guests</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo $reservation['reservation_id']; ?></td>
                        <td><?php echo $reservation['customer_id']; ?></td> 
                        <td><?php echo $reservation['customer_name']; ?></td>
                        <td><?php echo $reservation['reservation_date']; ?></td>
                        <td><?php echo $reservation['reservation_time']; ?></td>
                        <td><?php echo $reservation['num_guests']; ?></td>
                        <td>
                            <a href="edit_reservation.php?id=<?php echo $reservation['reservation_id']; ?>">Edit</a> | 
                            <a href="delete_reservation.php?id=<?php echo $reservation['reservation_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No reservations found.</p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2024 DineManager - Restaurant Reservation System</p>
</footer>

</body>
</html>

<?php
mysqli_close($conn);
?>
