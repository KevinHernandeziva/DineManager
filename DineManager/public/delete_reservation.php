<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservationId'])) {
    $reservationId = intval($_POST['reservationId']);

    if ($reservationId <= 0) {
        $error = 'Invalid reservation ID.';
    } else {
        $db = getDatabaseConnection();

        $stmt = $db->prepare("DELETE FROM Reservations WHERE reservationId = ?");
        $stmt->bind_param('i', $reservationId);

        if ($stmt->execute()) {
            $success = 'Reservation successfully deleted.';
        } else {
            $error = 'Error deleting reservation: ' . $stmt->error;
        }

        $stmt->close();
        $db->close();
    }
}

$db = getDatabaseConnection();
$result = $db->query("SELECT r.reservationId, r.reservationTime, c.customerName FROM Reservations r
                      JOIN Customers c ON r.customerId = c.customerId");

?>

<?php require_once 'includes/header.php'; ?>

<h2>Delete Reservation</h2>

<?php if ($error): ?>
    <p class="error" style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success" style="color: green;"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<h3>Select a Reservation to Delete</h3>

<form action="delete_reservation.php" method="POST">
    <label for="reservationId">Reservation:</label>
    <select id="reservationId" name="reservationId" required>
        <option value="">--Select Reservation--</option>
        <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?php echo $row['reservationId']; ?>">
                <?php echo htmlspecialchars($row['customerName'] . ' - ' . $row['reservationTime']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Delete Reservation</button>
</form>

<?php require_once 'includes/footer.php'; ?>
