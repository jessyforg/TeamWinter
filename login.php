<?php
include 'db.php';
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Query to fetch user by email
    $result = $conn->query("SELECT * FROM Users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if passwords match
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role']; // Store role in session

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admindashboard.php");
            } elseif ($user['role'] === 'customer') {
                header("Location: index.php");
            } else {
                $message = "Invalid user role.";
            }
            exit;
        } else {
            $message = "Invalid email or password.";
        }
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        input {
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
        <h1>Login</h1>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Login</button>
        </form>
        <p class="message"><?= $message ?></p>
        <p class="text-center">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
