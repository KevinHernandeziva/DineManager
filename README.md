# DineManager
Your one-stop solution to manage restaurant reservations and customers

Features
1. Add Reservation: Allows restaurant staff to add new reservations, including customer details, reservation time, and special requests.
2. View Reservations: View a list of all reservations with options to edit or delete them.
3. Search Reservations: Search reservations by Reservation ID, Customer ID, or Customer Name.
4. Customer Preferences: Update customer dining preferences, including special requests, favorite table, and dietary restrictions.
5. Dining Preferences: Enable customers to input their dining preferences and make it easier for the restaurant to accommodate their needs.

Installation
To install and set up DineManager, follow these steps:
Prerequisites

A local or remote web server 
PHP 7.4 or later
MySQL or MariaDB database server
XAMPP/WAMP or other PHP stack (optional for local setup)

Steps

Set up the database:
Create a new database in MySQL:
CREATE DATABASE restaurant_reservations;

Import the database schema:
Navigate to your phpMyAdmin or use the command line to create tables based on the restaurant_reservations.sql schema file (you can modify the SQL schema for your database).

Configure database connection:
Update the database connection settings in includes/db_connection.php:

$host = 'localhost'; // Database host
$username = 'root'; // Database username
$password = ''; // Database password (use 'root' or another password for non-local databases)
$dbname = 'restaurant_reservations'; // Your database name

Upload the files to your web server:
If you're using XAMPP/WAMP, move the DineManager folder to the htdocs or www directory.

Access the application:
Open your browser and navigate to:
http://localhost/DineManager/public/index.php    
