<?php
include('../includes/db_connection.php');

$error_message = null;
$success_message = null;
$customer_id = null;
$reservation_id = null;
$customer_name = null;
$favorite_table = null;
$dietary_restrictions = null;

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

if (isset($_POST['update_special_requests']) && isset($reservation_id)) {
    $favorite_table = mysqli_real_escape_string($conn, $_POST['favorite_table']);
    $dietary_restrictions = mysqli_real_escape_string($conn, $_POST['dietary_restrictions']);

    $check_query = "SELECT * FROM DiningPreferences WHERE customerId = $customer_id";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        $update_query = "UPDATE DiningPreferences
                         SET favoriteTable = '$favorite_table', dietaryRestrictions = '$dietary_restrictions'
                         WHERE customerId = $customer_id";

        if (mysqli_query($conn, $update_query)) {
            $success_message = "Dining preferences updated successfully!";
        } else {
            $error_message = "Error updating dining preferences: " . mysqli_error($conn);
        }
    } else {
        $insert_query = "INSERT INTO DiningPreferences (customerId, favoriteTable, dietaryRestrictions)
                         VALUES ($customer_id, '$favorite_table', '$dietary_restrictions')";

        if (mysqli_query($conn, $insert_query)) {
            $success_message = "Dining preferences saved successfully!";
        } else {
            $error_message = "Error saving dining preferences: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dining Preferences - DineManager</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<header>
    <h1>Dining Preferences</h1>
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
    <h2>Search and Update Dining Preferences</h2>

    <form method="POST" action="dining_preferences.php">
        <label for="search_term">Enter Reservation ID, Customer ID, or Customer Name:</label>
        <input type="text" id="search_term" name="search_term" value="<?php echo isset($search_term) ? htmlspecialchars($search_term) : ''; ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
    <?php if (isset($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>

    <?php if (isset($reservation_id)): ?>
        <h3>Update Dining Preferences for Customer: <?php echo htmlspecialchars($customer_name); ?></h3>

        <form method="POST" action="dining_preferences.php">
            <input type="hidden" name="search_term" value="<?php echo htmlspecialchars($search_term); ?>">

            <label for="favorite_table">Favorite Table:</label>
            <input type="text" id="favorite_table" name="favorite_table" value="<?php echo htmlspecialchars($favorite_table); ?>" required><br><br>

            <label for="dietary_restrictions">Dietary Restrictions:</label>
            <textarea id="dietary_restrictions" name="dietary_restrictions" rows="4" cols="50" required><?php echo htmlspecialchars($dietary_restrictions); ?></textarea><br><br>

            <input type="submit" name="update_special_requests" value="Update Preferences">
        </form>
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
