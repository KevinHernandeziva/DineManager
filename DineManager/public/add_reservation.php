<?php
include('../includes/db_connection.php');

$reservation_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']); 
    $reservation_date = mysqli_real_escape_string($conn, $_POST['reservation_date']);
    $reservation_time = mysqli_real_escape_string($conn, $_POST['reservation_time']);
    $num_guests = mysqli_real_escape_string($conn, $_POST['num_guests']);
    $special_requests = mysqli_real_escape_string($conn, $_POST['special_requests']);

    $query_insert_customer = "INSERT INTO customers (customer_name, contact_info) VALUES ('$customer_name', '$contact_info')";
    
    if (mysqli_query($conn, $query_insert_customer)) {
        $customer_id = mysqli_insert_id($conn);
        
        $query_insert_reservation = "INSERT INTO reservations (customer_name, reservation_date, reservation_time, num_guests, special_requests, customer_id)
                                     VALUES ('$customer_name', '$reservation_date', '$reservation_time', '$num_guests', '$special_requests', '$customer_id')";
        
        if (mysqli_query($conn, $query_insert_reservation)) {
            $reservation_id = mysqli_insert_id($conn);
            
            $reservation_success = true;
        } else {
            echo "Error adding reservation: " . mysqli_error($conn);
        }
    } else {
        echo "Error adding customer: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Reservation - DineManager</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<header>
    <h1>Add New Reservation</h1>
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
    <h2>Add New Reservation</h2>

    <form method="POST" action="add_reservation.php">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="contact_info">Contact Information (Phone/Email):</label>
        <input type="text" id="contact_info" name="contact_info" required><br><br>

        <label for="reservation_date">Reservation Date:</label>
        <input type="date" id="reservation_date" name="reservation_date" required><br><br>

        <label for="reservation_time">Reservation Time:</label>
        <input type="time" id="reservation_time" name="reservation_time" required><br><br>

        <label for="num_guests">Number of Guests:</label>
        <input type="number" id="num_guests" name="num_guests" required><br><br>

        <label for="special_requests">Special Requests:</label>
        <textarea id="special_requests" name="special_requests"></textarea><br><br>

        <input type="submit" value="Add Reservation">
    </form>

    <?php if ($reservation_success): ?>
        <div class="confirmation">
            <h3>Reservation Successful!</h3>
            <p>Your reservation ID is: <strong><?php echo $reservation_id; ?></strong></p>
            <p>Your customer ID is: <strong><?php echo $customer_id; ?></strong></p>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p> DineManager - Restaurant Reservation System</p>
</footer>

</body>
</html>

<?php
mysqli_close($conn);
?>
