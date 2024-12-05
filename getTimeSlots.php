<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booking_system";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$date = $_GET['date'];
$therapist_id = $_GET['therapist_id'];


$sql = "SELECT start_time, end_time FROM Availability WHERE therapist_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $therapist_id, $date);
$stmt->execute();
$result = $stmt->get_result();


$options = "<option value=''>Select Time Slot</option>";
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='{$row['start_time']}'>{$row['start_time']} - {$row['end_time']}</option>";
}

echo $options;

$stmt->close();
$conn->close();
?>