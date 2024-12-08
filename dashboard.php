<?php
include 'db.php';
session_start();
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "booking_system";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$user_id = $_SESSION['user_id']; 
$user = $conn->query("SELECT * FROM Users WHERE user_id = $user_id")->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);


    $result = $conn->query("SELECT * FROM Users WHERE email = '$email' AND user_id != $user_id");

    if ($result->num_rows > 0) {
    
        $profile_message = "The email is already in use by another user.";
    } else {
        $conn->query("
            UPDATE Users 
            SET full_name = '$full_name', email = '$email', phone_number = '$phone_number'
            WHERE user_id = $user_id
        ");
        $user = $conn->query("SELECT * FROM Users WHERE user_id = $user_id")->fetch_assoc();
        $profile_message = "Profile updated successfully.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($current_password === $user['password']) {
        if ($new_password === $confirm_password) {
            $conn->query("UPDATE Users SET password = '$new_password' WHERE user_id = $user_id");
            $password_message = "Password updated successfully.";
        } else {
            $password_message = "New password and confirm password do not match.";
        }
    } else {
        $password_message = "Current password is incorrect.";
    }
}

$upcoming_appointments = $conn->query("
    SELECT a.*, s.service_name, u.full_name AS therapist_name
    FROM Appointments a
    JOIN Services s ON a.service_id = s.service_id
    JOIN Users u ON a.therapist_id = u.user_id
    WHERE a.user_id = $user_id AND a.appointment_date >= CURDATE()
    ORDER BY a.appointment_date ASC
");

$past_appointments = $conn->query("
    SELECT a.*, s.service_name, u.full_name AS therapist_name
    FROM Appointments a
    JOIN Services s ON a.service_id = s.service_id
    JOIN Users u ON a.therapist_id = u.user_id
    WHERE a.user_id = $user_id AND a.appointment_date < CURDATE()
    ORDER BY a.appointment_date DESC
");

$promotions = [
    [
        'promo_code' => 'WELCOME10',
        'description' => 'Get 10% off on your first booking!',
        'valid_until' => '2024-12-31'
    ],
    [
        'promo_code' => 'LOYALTY15',
        'description' => 'Enjoy 15% off for loyal customers with 5+ appointments!',
        'valid_until' => '2025-01-31'
    ]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function showEditProfile() {
            document.getElementById('edit-profile-modal').classList.remove('hidden');
        }

        function closeEditProfile() {
            document.getElementById('edit-profile-modal').classList.add('hidden');
        }
    </script>
    <style>
        :root {
            --primary-color: white;
            --secondary-color: black;
            --background-color: white;
            --text-color: black;
            --accent-color: #008CBA;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to bottom, #FDF7F4, #8EB486, #997C70, #685752);
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }
    
        header {
            background-color: var(#FDF7F4);
            color: var(--text-color);
            text-align: center;
            padding: 1rem 0;
            margin-top: 100px
        }
        .container {
            padding: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        h2 {
            color: var(--text-color);
        }
        .card {
            background-color: var(--background-color);
            border-radius: 8px;
            box-shadow: 14px 14px 16px rgba(0.1, 0.1, 0.1, 0.1);
            margin-bottom: 1rem;
            padding: 2rem;
            border: 1px solid black;
        }
        button {
            background-color: var(--accent-color);
            color: var(--text-color);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: var(--primary-color);
            border: 1px solid black;
        }
        input, label {
            display: block;
            width: 100%;
            margin-bottom: 0.5rem;
        }
        input {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            
        }
        .hidden {
            display: none;
        }
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--background-color);
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--background-color);
            z-index: 999;
        }
        .grid {
            display: grid;
            gap: 1rem;
        }
        @media (min-width: 768px) {
            .grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        .navbar {
        background-color: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .navbar:hover {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand {
            font-weight: bold;
            color: #000;
            font-size: 1.5rem;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.2);
            color: #ff69b4;
        }

        .navbar-nav .nav-link {
            color: #000;
            font-size: 1.1rem;
            text-transform: uppercase;
            padding: 10px;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            transform: scale(1.1);
            color: #ff69b4;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Winter Spa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="book.php">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Popular Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <header>
        <h1>Welcome, <?= htmlspecialchars($user['full_name']) ?></h1>
    </header>
    <div class="container">
        <h2>Upcoming Appointments</h2>
        <div class="grid">
            <?php if ($upcoming_appointments->num_rows > 0): ?>
                <?php while ($row = $upcoming_appointments->fetch_assoc()): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($row['service_name']) ?></h3>
                        <p>Therapist: <?= htmlspecialchars($row['therapist_name']) ?></p>
                        <p>Date: <?= htmlspecialchars($row['appointment_date']) ?></p>
                        <p>Time: <?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></p>
                        <button>Cancel</button>
                        <button>Reschedule</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No upcoming appointments yet.</p>
            <?php endif; ?>
        </div>

        <h2>Past Appointments</h2>
        <div class="grid">
            <?php if ($past_appointments->num_rows > 0): ?>
                <?php while ($row = $past_appointments->fetch_assoc()): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($row['service_name']) ?></h3>
                        <p>Therapist: <?= htmlspecialchars($row['therapist_name']) ?></p>
                        <p>Date: <?= htmlspecialchars($row['appointment_date']) ?></p>
                        <p>Time: <?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></p>
                        <button>Leave a Review</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No past appointments found.</p>
            <?php endif; ?>
        </div>

        <h2>Account Settings</h2>
        <div class="card">
            <h3>Profile</h3>
            <p>Name: <?= htmlspecialchars($user['full_name']) ?></p>
            <p>Email: <?= htmlspecialchars($user['email']) ?></p>
            <p>Phone: <?= htmlspecialchars($user['phone_number']) ?></p>
            <button onclick="showEditProfile()">Edit Profile</button>
        </div>

        <div id="edit-profile-modal" class="hidden">
            <div class="modal-backdrop" onclick="closeEditProfile()"></div>
            <div class="modal">
                <h3>Edit Profile</h3>
                <form method="POST" action="">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>

                    <button type="submit" name="update_profile">Save Changes</button>
                    <button type="button" onclick="closeEditProfile()">Cancel</button>
                </form>
                <?php if (isset($profile_message)): ?>
                    <p><?= htmlspecialchars($profile_message) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <h2>Change Password</h2>
        <div class="card">
            <form method="POST" action="">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" required>

                <label for="new_password">New Password</label>
                <input type="password" name="new_password" required>

                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" required>

                <button type="submit" name="change_password">Change Password</button>
            </form>
            <?php if (isset($password_message)): ?>
                <p><?= htmlspecialchars($password_message) ?></p>
            <?php endif; ?>
        </div>

        <h2>Promotions</h2>
        <div class="grid">
            <?php foreach ($promotions as $promo): ?>
                <div class="card">
                    <h3>Promo Code: <?= htmlspecialchars($promo['promo_code']) ?></h3>
                    <p><?= htmlspecialchars($promo['description']) ?></p>
                    <p>Valid Until: <?= htmlspecialchars($promo['valid_until']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
