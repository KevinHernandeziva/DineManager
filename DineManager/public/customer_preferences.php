<?php
include('../includes/db_connection.php');

$reservation_id = null;
$customer_name = null;
$special_requests = null;
$error_message = null;
$success_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    
    $search_by_id = is_numeric($search_term);  
    
    if ($search_by_id) {
        $query = "SELECT r.reservation_id, r.special_requests, c.customer_name, c.customer_id
                  FROM reservations r
                  JOIN customers c ON r.customer_id = c.customer_id
                  WHERE r.reservation_id = $search_term OR c.customer_id = $search_term";
    } else {
        $query = "SELECT r.reservation_id, r.special_requests, c.customer_name, c.customer_id
                  FROM reservations r
                  JOIN customers c ON r.customer_id = c.customer_id
                  WHERE c.customer_name LIKE '%$search_term%'";
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $reservation_id = $row['reservation_id'];
        $customer_name = $row['customer_name'];
        $special_requests = $row['special_requests'];
        $customer_id = $row['customer_id'];
    } else {
        $error_message = "No reservation found for the provided ID or customer name.";
    }
}

if (isset($_POST['update_special_requests'])) {
    $new_special_requests = mysqli_real_escape_string($conn, $_POST['special_requests']);

    $update_query = "UPDATE reservations
                     SET special_requests = '$new_special_requests'
                     WHERE reservation_id = $reservation_id";

    if (mysqli_query($conn, $update_query)) {
        $success_message = "Special requests updated successfully!";
        header("Refresh: 2; url=customer_preferences.php");  // Redirect to refresh the page
        exit();
    } else {
        $error_message = "Error updating special requests: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Special Requests</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<header>
    <h1>Update Special Requests</h1>
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
    <h2>Search for your Reservation</h2>
    <form method="POST" action="customer_preferences.php">
        <label for="search_term">Enter Reservation ID, Customer ID, or Customer Name:</label>
        <input type="text" id="search_term" name="search_term" value="<?php echo isset($search_term) ? htmlspecialchars($search_term) : ''; ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
    <?php if (isset($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>

    <?php if (isset($reservation_id)): ?>
        <h3>Update Special Requests for Reservation ID: <?php echo htmlspecialchars($reservation_id); ?> (Customer: <?php echo htmlspecialchars($customer_name); ?>)</h3>
        
        <form method="POST" action="customer_preferences.php">
            <input type="hidden" name="search_term" value="<?php echo htmlspecialchars($search_term); ?>">

            <label for="special_requests">Update Special Request</label><br>
            <textarea id="special_requests" name="special_requests" rows="4" cols="50"><?php echo htmlspecialchars($special_requests); ?></textarea><br><br>
            <input type="submit" name="update_special_requests" value="Update Special Requests">
        </form>
    <?php endif; ?>
</div>

<footer>
    <p>DineManager Restaurant Reservation System</p>
</footer>

</body>
</html>

<?php
mysqli_close($conn);
?>
