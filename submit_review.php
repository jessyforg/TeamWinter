<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];
    $appointment_id = $_POST['appointment_id'];
    $rating = $_POST['rating'];
    $review_text = $conn->real_escape_string($_POST['comment']);

    // Insert the review into the database
    $conn->query("
        INSERT INTO Reviews (user_id, appointment_id, rating, comment, created_at)
        VALUES ($user_id, $appointment_id, $rating, '$review_text', NOW())
    ");

    // Redirect back to the user dashboard or show a success message
    header("Location: dashboard.php");  // assuming the dashboard is dashboard.php
    exit;
}
?>