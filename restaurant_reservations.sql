-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 08:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_reservations`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `addReservation` (IN `customerName` VARCHAR(45), IN `contactInfo` VARCHAR(200), IN `reservationTime` DATETIME, IN `numberOfGuests` INT, IN `specialRequests` VARCHAR(200))   BEGIN
    DECLARE customerId INT;

    -- Check if the customer exists
    SELECT Customers.customerId INTO customerId
    FROM Customers WHERE customerName = customerName AND contactInfo = contactInfo;

    -- If not exists, insert customer
    IF customerId IS NULL THEN
        INSERT INTO Customers (customerName, contactInfo) VALUES (customerName, contactInfo);
        SET customerId = LAST_INSERT_ID();
    END IF;

    -- Insert reservation
    INSERT INTO Reservations (customerId, reservationTime, numberOfGuests, specialRequests)
    VALUES (customerId, reservationTime, numberOfGuests, specialRequests);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addSpecialRequest` (IN `reservationId` INT, IN `requests` VARCHAR(200))   BEGIN
    UPDATE Reservations SET specialRequests = requests WHERE reservationId = reservationId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `findReservations` (IN `customerId` INT)   BEGIN
    SELECT * FROM Reservations WHERE customerId = customerId;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `contact_info` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `contact_info`) VALUES
(1, 'Kevin Hernandez', NULL),
(2, 'Yanilda Peralta', NULL),
(3, 'Kevin Hernandez', '3476949550'),
(4, 'Kevin Hernandez', '3476949550'),
(5, 'Kevin Hernandez', '3476949550'),
(6, 'Kevin Hernandez', '3476949550'),
(7, 'Kevin Hernandez', '3476949550'),
(8, 'Yanilda Peralta', '3476959550'),
(9, 'Kevin Hernandez', '3476949550'),
(10, 'Kevin Hernandez', '3476949550'),
(11, 'Kevin Hernandez', '3476949550'),
(12, 'Kevin Hernandez', '3476949550');

-- --------------------------------------------------------

--
-- Table structure for table `customer_preferences`
--

CREATE TABLE `customer_preferences` (
  `preference_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `preference_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diningpreferences`
--

CREATE TABLE `diningpreferences` (
  `preferenceId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `favoriteTable` varchar(45) DEFAULT NULL,
  `dietaryRestrictions` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diningpreferences`
--

INSERT INTO `diningpreferences` (`preferenceId`, `customerId`, `favoriteTable`, `dietaryRestrictions`) VALUES
(1, 11, '2', 'no fish\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` datetime NOT NULL,
  `num_guests` int(11) NOT NULL,
  `special_requests` varchar(200) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customerId` (`customer_id`);

--
-- Indexes for table `customer_preferences`
--
ALTER TABLE `customer_preferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `diningpreferences`
--
ALTER TABLE `diningpreferences`
  ADD PRIMARY KEY (`preferenceId`),
  ADD UNIQUE KEY `preferenceId` (`preferenceId`),
  ADD KEY `customerId` (`customerId`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD UNIQUE KEY `reservationId` (`reservation_id`),
  ADD KEY `customerId` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer_preferences`
--
ALTER TABLE `customer_preferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diningpreferences`
--
ALTER TABLE `diningpreferences`
  MODIFY `preferenceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_preferences`
--
ALTER TABLE `customer_preferences`
  ADD CONSTRAINT `customer_preferences_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `diningpreferences`
--
ALTER TABLE `diningpreferences`
  ADD CONSTRAINT `diningpreferences_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
