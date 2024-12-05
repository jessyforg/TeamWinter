<?php
session_start();
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "booking_system";


$conn = new mysqli($servername, $username, $password, $dbname);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$service_id = $_POST['service_id'];
$therapist_id = $_POST['therapist_id'];
$appointment_date = $_POST['appointment_date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];


$user_id = $_SESSION['user_id']; 


$sql = "INSERT INTO Appointments (user_id, therapist_id, service_id, appointment_date, start_time, end_time, status) 
        VALUES ($user_id, $therapist_id, $service_id, '$appointment_date', '$start_time', '$end_time', 'pending')";

if ($conn->query($sql) === TRUE) {
    $response = [
        'status' => 'success',
        'message' => 'Appointment confirmed!'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Error confirming appointment: ' . $conn->error
    ];
}


$conn->close();


echo json_encode($response);
?>
