<?php
include 'db.php';
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "booking_system";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$availability_id = isset($_GET['availability_id']) ? intval($_GET['availability_id']) : null;
$therapist_id = isset($_GET['therapist_id']) ? intval($_GET['therapist_id']) : null;
$message = '';

if ($availability_id) {
    $availability = $conn->query("SELECT * FROM Availability WHERE availability_id = $availability_id")->fetch_assoc();
} else {
    $availability = ['date' => '', 'start_time' => '', 'end_time' => ''];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $conn->real_escape_string($_POST['date']);
    $start_time = $conn->real_escape_string($_POST['start_time']);
    $end_time = $conn->real_escape_string($_POST['end_time']);
    
    if ($availability_id) {
        $query = "UPDATE Availability SET date = '$date', start_time = '$start_time', end_time = '$end_time'
                  WHERE availability_id = $availability_id";
        $conn->query($query);
        $message = "Availability updated successfully.";
    } else {
        $query = "INSERT INTO Availability (therapist_id, date, start_time, end_time) 
                  VALUES ($therapist_id, '$date', '$start_time', '$end_time')";
        $conn->query($query);
        $message = "New availability added successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Therapist Availability</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
            padding: 0; 
        }
        .container { 
            max-width: 800px; 
            margin: 2rem auto; 
            background: #fff; 
            padding: 1rem; 
            border-radius: 8px; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
        }
        h2 { 
            color: #333; 
        }
        form { 
            display: flex; 
            flex-direction: column; 
        }
        label { 
            margin: 0.5rem 0; 
            font-weight: bold; 
        }
        input { 
            padding: 0.5rem; 
            margin: 0.5rem 0; 
        }
        button { 
            background-color: #333; 
            color: white; 
            padding: 0.5rem 1rem; 
            border: none; 
            cursor: pointer; 
            margin-top: 1rem; 
        }
        button:hover { 
            background-color: #555; 
        }
        .message { 
            color: green; 
            margin-bottom: 1rem; 
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #333;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <a href="admindashboard.php" class="back-button">Back to Dashboard</a>

    <div class="container">
        <h2><?= $availability_id ? "Edit Availability" : "Add New Availability" ?></h2>

        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="date">Date:</label>
            <input type="date" name="date" value="<?= htmlspecialchars($availability['date']) ?>" required>

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" value="<?= htmlspecialchars($availability['start_time']) ?>" required>

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" value="<?= htmlspecialchars($availability['end_time']) ?>" required>

            <button type="submit"><?= $availability_id ? "Update Availability" : "Add Availability" ?></button>
        </form>
    </div>
</body>
</html>