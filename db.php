<?php
// Define constants for DB connection parameters
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'booking_system');

// Create a connection using MySQLi (improved)
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    // For development, show the error. In production, log the error and display a generic message.
    error_log("Connection failed: " . $conn->connect_error); // Log the error
    die("Connection failed: Unable to connect to the database. Please try again later.");
}

// Optionally, set the character set to ensure correct encoding
$conn->set_charset("utf8mb4");

?>