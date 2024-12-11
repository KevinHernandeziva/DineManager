CREATE DATABASE IF NOT EXISTS restaurant_reservations;
USE restaurant_reservations;

CREATE TABLE Customers (
    customerId INT NOT NULL UNIQUE AUTO_INCREMENT,
    customerName VARCHAR(45) NOT NULL,
    contactInfo VARCHAR(200),
    PRIMARY KEY (customerId)
);

CREATE TABLE Reservations (
    reservationId INT NOT NULL UNIQUE AUTO_INCREMENT,
    customerId INT NOT NULL,
    reservationTime DATETIME NOT NULL,
    numberOfGuests INT NOT NULL,
    specialRequests VARCHAR(200),
    PRIMARY KEY (reservationId),
    FOREIGN KEY (customerId) REFERENCES Customers(customerId) ON DELETE CASCADE
);

CREATE TABLE DiningPreferences (
    preferenceId INT NOT NULL UNIQUE AUTO_INCREMENT,
    customerId INT NOT NULL,
    favoriteTable VARCHAR(45),
    dietaryRestrictions VARCHAR(200),
    PRIMARY KEY (preferenceId),
    FOREIGN KEY (customerId) REFERENCES Customers(customerId) ON DELETE CASCADE
);

DELIMITER //
CREATE PROCEDURE findReservations(IN customerId INT)
BEGIN
    SELECT * FROM Reservations WHERE customerId = customerId;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE addSpecialRequest(IN reservationId INT, IN requests VARCHAR(200))
BEGIN
    UPDATE Reservations SET specialRequests = requests WHERE reservationId = reservationId;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE addReservation(
    IN customerName VARCHAR(45),
    IN contactInfo VARCHAR(200),
    IN reservationTime DATETIME,
    IN numberOfGuests INT,
    IN specialRequests VARCHAR(200)
)
BEGIN
    DECLARE customerId INT;

    SELECT Customers.customerId INTO customerId
    FROM Customers WHERE customerName = customerName AND contactInfo = contactInfo;

    IF customerId IS NULL THEN
        INSERT INTO Customers (customerName, contactInfo) VALUES (customerName, contactInfo);
        SET customerId = LAST_INSERT_ID();
    END IF;

    INSERT INTO Reservations (customerId, reservationTime, numberOfGuests, specialRequests)
    VALUES (customerId, reservationTime, numberOfGuests, specialRequests);
END //
DELIMITER ;