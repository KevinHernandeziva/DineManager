<?php
require_once '../includes/config.php';
require_once '../includes/RestaurantDatabase.php';

$reservationId = $_GET['id'] ?? null;
$reservation = null;
$updateSuccess = false;

if ($reservationId) {
    $conn = getDatabaseConnection();
    $result = $conn->query("SELECT * FROM Reservations WHERE reservationId = $reservationId");
    
    if ($result->num_rows > 0) {
        $reservation = $result->fetch_assoc();
    } else {
        die("Reservation not found.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $numberOfGuests = $_POST['numberOfGuests'];
        $specialRequests = $_POST['specialRequests'];

        $updateQuery = "UPDATE Reservations SET 
                            numberOfGuests = '$numberOfGuests', 
                            specialRequests = '$specialRequests' 
                        WHERE reservationId = $reservationId";

        if ($conn->query($updateQuery)) {
            $updateSuccess = true;
        } else {
            $updateSuccess = false;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Reservation - DineManager</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h1>Update Reservation</h1>

        <?php if ($reservation): ?>
            <?php if ($updateSuccess): ?>
                <p class="success-message">Reservation updated successfully!</p>
            <?php endif; ?>

            <form action="update_reservation.php?id=<?php echo $reservation['reservationId']; ?>" method="POST">
                <label for="numberOfGuests">Number of Guests:</label>
                <input type="number" name="numberOfGuests" id="numberOfGuests" value="<?php echo htmlspecialchars($reservation['numberOfGuests']); ?>" required>

                <label for="specialRequests">Special Requests:</label>
                <textarea name="specialRequests" id="specialRequests" rows="4"><?php echo htmlspecialchars($reservation['specialRequests']); ?></textarea>

                <button type="submit" class="btn">Update Reservation</button>
            </form>

            <br>
            <a href="view_reservations.php" class="btn">Back to Reservations</a>
        <?php else: ?>
            <p>Reservation not found.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
