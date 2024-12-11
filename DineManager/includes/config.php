<?php
define('DB_HOST', 'localhost');        
define('DB_USER', 'root');             
define('DB_PASSWORD', '');             
define('DB_NAME', 'restaurant_reservations'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getDatabaseConnection() {
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}
?>
