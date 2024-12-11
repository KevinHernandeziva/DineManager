<?php
include('db_connection.php');

function getAllReservations() {
    global $conn;
    $query = "SELECT * FROM reservations";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error fetching reservations: " . mysqli_error($conn));
    }

    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }

    return $reservations;
}

function addReservation($customer_name, $reservation_date, $reservation_time, $num_guests) {
    global $conn;
    $query = "INSERT INTO reservations (customer_name, reservation_date, reservation_time, num_guests)
              VALUES ('$customer_name', '$reservation_date', '$reservation_time', '$num_guests')";
    
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        die("Error adding reservation: " . mysqli_error($conn));
    }
}

function updateReservation($reservation_id, $customer_name, $reservation_date, $reservation_time, $num_guests) {
    global $conn;
    $query = "UPDATE reservations
              SET customer_name = '$customer_name', reservation_date = '$reservation_date', 
                  reservation_time = '$reservation_time', num_guests = '$num_guests'
              WHERE reservation_id = $reservation_id";
    
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        die("Error updating reservation: " . mysqli_error($conn));
    }
}

function deleteReservation($reservation_id) {
    global $conn;
    $query = "DELETE FROM reservations WHERE reservation_id = $reservation_id";
    
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        die("Error deleting reservation: " . mysqli_error($conn));
    }
}

function searchReservations($search_term) {
    global $conn;
    $query = "SELECT * FROM reservations WHERE customer_name LIKE '%$search_term%'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error searching reservations: " . mysqli_error($conn));
    }

    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }

    return $reservations;
}

function getReservationById($reservation_id) {
    global $conn;
    $query = "SELECT * FROM reservations WHERE reservation_id = $reservation_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error fetching reservation by ID: " . mysqli_error($conn));
    }

    return mysqli_fetch_assoc($result);
}

?>
