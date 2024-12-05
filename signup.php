<?php
include 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $password = $conn->real_escape_string($_POST['password']);

    $check_email = $conn->query("SELECT * FROM Users WHERE email = '$email'");
    if ($check_email->num_rows > 0) {
        $message = "Email is already registered.";
    } else {
        $role = 'customer'; 
        $conn->query("
            INSERT INTO Users (full_name, email, phone_number, password, role)
            VALUES ('$full_name', '$email', '$phone_number', '$password', '$role')
        ");
        $message = "Account created successfully. <a href='login.php'>Login here</a>.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <h1>Signup</h1>
    <form method="POST">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" id="full_name" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Signup</button>
    </form>
    <p><?= $message ?></p>
    <p>Already have an account? <a href="login.php">Login</a>.</p>
</body>
</html>
