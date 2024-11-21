<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booking_system";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->select_db($dbname);

$tableQueries = [
    "CREATE TABLE Users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone_number VARCHAR(15),
        pass VARCHAR(255) NOT NULL,
        roles ENUM('customer', 'therapist', 'admin') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "CREATE TABLE Services (
        service_id INT AUTO_INCREMENT PRIMARY KEY,
        serv_name VARCHAR(100) NOT NULL,
        descr TEXT,
        duration INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "CREATE TABLE Appointments (
        appointment_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        therapist_id INT NOT NULL,
        service_id INT NOT NULL,
        appointment_date DATE NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        stat ENUM('pending', 'confirmed', 'completed', 'canceled') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES Users(user_id),
        FOREIGN KEY (therapist_id) REFERENCES Users(user_id),
        FOREIGN KEY (service_id) REFERENCES Services(service_id)
    )",
    "CREATE TABLE Payments (
        payment_id INT AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        payment_method ENUM('cash', 'credit_card', 'paypal') NOT NULL,
        payment_status ENUM('paid', 'unpaid', 'refunded') NOT NULL DEFAULT 'unpaid',
        transaction_id VARCHAR(100) UNIQUE,
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id)
    )",
    "CREATE TABLE Availability1 (
        availability_id INT AUTO_INCREMENT PRIMARY KEY,
        therapist_id INT NOT NULL,
        dates DATE NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        FOREIGN KEY (therapist_id) REFERENCES Users(user_id)
    )",
    "CREATE TABLE Reviews (
        review_id INT AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT NOT NULL,
        user_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id),
        FOREIGN KEY (user_id) REFERENCES Users(user_id)
    )",
    "CREATE TABLE Promotions (
        promo_id INT AUTO_INCREMENT PRIMARY KEY,
        promo_code VARCHAR(50) NOT NULL UNIQUE,
        descr TEXT,
        discount_percent DECIMAL(5, 2) NOT NULL CHECK (discount_percent >= 0 AND discount_percent <= 100),
        init_date DATE NOT NULL,
        end_date DATE NOT NULL
    )"
];

foreach ($tableQueries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Table created successfully\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
}

$conn->close();

?>