<?php
include 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (!empty($full_name) && !empty($email) && !empty($password) && !empty($role)) {
        $check_email = $conn->prepare("SELECT * FROM Users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();

        if ($result->num_rows > 0) {
            $message = "Email is already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO Users (full_name, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $full_name, $email, $phone_number, $password, $role);

            if ($stmt->execute()) {
                $message = "Account created successfully. <a href='login.php'>Login here</a>.";
            } else {
                $message = "Error: Unable to create account. Please try again.";
            }
            $stmt->close();
        }
        $check_email->close();
    } else {
        $message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to bottom, #FDF7F4, #8EB486, #997C70, #685752);
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .text-center {
            text-align: center;
        }
        .text-center a {
            color: #007bff;
            text-decoration: none;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
        .message {
            margin-top: 15px;
            color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="container">
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
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="">Select Role</option>
                <option value="customer">Customer</option>
                <option value="therapist">Therapist</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Signup</button>
        </form>
        <p class="message"><?= $message ?></p>
        <p class="text-center">Already have an account? <a href="login.php">Login</a>.</p>
    </div>
</body>
</html>
