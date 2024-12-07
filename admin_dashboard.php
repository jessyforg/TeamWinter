<?php
include 'db.php';
session_start();
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "booking_system";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM Admins WHERE admin_id = $admin_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $service_name = $conn->real_escape_string($_POST['service_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $duration = intval($_POST['duration']);
    $price = floatval($_POST['price']);

    $conn->query("INSERT INTO Services (service_name, description, duration, price) VALUES ('$service_name', '$description', $duration, $price)");
    $message = "Service added successfully.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $conn->query("UPDATE Appointments SET status = 'confirmed' WHERE appointment_id = $appointment_id");
        $message = "Booking approved.";
    } elseif ($action === 'cancel') {
        $conn->query("UPDATE Appointments SET status = 'canceled' WHERE appointment_id = $appointment_id");
        $message = "Booking canceled.";
    } elseif ($action === 'reschedule') {
        $message = "Booking rescheduled.";
    }
}

$services = $conn->query("SELECT * FROM Services");
$bookings = $conn->query("
    SELECT a.*, u.full_name AS customer_name, t.full_name AS therapist_name, s.service_name 
    FROM Appointments a 
    JOIN Users u ON a.user_id = u.user_id 
    JOIN Users t ON a.therapist_id = t.user_id 
    JOIN Services s ON a.service_id = s.service_id
");

$therapists = $conn->query("SELECT * FROM Users WHERE role = 'therapist'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { 
            font-family: 'Roboto', sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
            padding: 0; 
        }
        header { 
            background-color: #333; 
            color: white; 
            text-align: center; 
            padding: 1rem 0; 
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 1rem; 
        }
        h2 { 
            color: #333; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 1rem; 
        }
        table, th, td { 
            border: 1px solid #ddd; 
            padding: 0.5rem; 
        }
        th { 
            background-color: #f4f4f4; 
        }
        button { 
            background-color: #333; 
            color: white; 
            border: none; 
            padding: 0.5rem 1rem; 
            cursor: pointer; 
        }
        button:hover { 
            background-color: #555; 
        }
        input, textarea, select { 
            width: 100%; 
            padding: 0.5rem; 
            margin: 0.5rem 0; 
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?= htmlspecialchars($admin['full_name']) ?></h1>
        <p>Admin Dashboard</p>
    </header>

    <div class="container">
        <?php if (isset($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <section>
            <h2>Manage Services</h2>
            <table>
                <tr>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
                <?php while ($service = $services->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($service['service_name']) ?></td>
                    <td><?= htmlspecialchars($service['description']) ?></td>
                    <td><?= htmlspecialchars($service['duration']) ?> mins</td>
                    <td><?= htmlspecialchars($service['price']) ?></td>
                    <td>
                        <a href="edit_service.php?id=<?= $service['service_id'] ?>">Edit</a>
                        <a href="delete_service.php?id=<?= $service['service_id'] ?>">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

            <h3>Add New Service</h3>
            <form method="POST">
                <label for="service_name">Service Name:</label>
                <input type="text" name="service_name" required>

                <label for="description">Description:</label>
                <textarea name="description" required></textarea>

                <label for="duration">Duration (minutes):</label>
                <input type="number" name="duration" required>

                <label for="price">Price:</label>
                <input type="text" name="price" required>

                <button type="submit" name="add_service">Add Service</button>
            </form>
        </section>

        <section>
            <h2>Manage Bookings</h2>
            <table>
                <tr>
                    <th>Customer Name</th>
                    <th>Therapist Name</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php while ($booking = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                    <td><?= htmlspecialchars($booking['therapist_name']) ?></td>
                    <td><?= htmlspecialchars($booking['service_name']) ?></td>
                    <td><?= htmlspecialchars($booking['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($booking['start_time']) ?> - <?= htmlspecialchars($booking['end_time']) ?></td>
                    <td><?= htmlspecialchars($booking['status']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="appointment_id" value="<?= $booking['appointment_id'] ?>">
                            <button type="submit" name="action" value="approve">Approve</button>
                            <button type="submit" name="action" value="cancel">Cancel</button>
                            <button type="submit" name="action" value="reschedule">Reschedule</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <section>
            <h2>Therapist Schedule Management</h2>
            <table>
                <tr>
                    <th>Therapist Name</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
                <?php while ($therapist = $therapists->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($therapist['full_name']) ?></td>
                    <td><?= "View Availability" ?></td>
                    <td>
                        <a href="edit_availability.php?therapist_id=<?= $therapist['user_id'] ?>">Edit Availability</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <section>
            <h2>Payment and Reports</h2>
            <p>Coming soon...</p>
        </section>
    </div>
</body>
</html>
