<?php

$host = 'localhost'; 
$db = 'booking_system';
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price'; 
$order_by = 'price'; 

switch ($sort) {
    case 'price':
        $order_by = 'price';
        break;
    case 'duration':
        $order_by = 'duration';
        break;
    default:
        $order_by = 'price'; 
}


$sql = "SELECT * FROM Services ORDER BY $order_by";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to bottom, #FDF7F4, #8EB486, #997C70, #685752);
    color: #333;
    min-height: 100vh; 
}


        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .filters {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter {
            margin: 10px 0;
            flex: 1;
            min-width: 200px;
        }

        .service-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .service-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            width: calc(30% - 40px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #fff;
        }
        .service-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .service-card h3 {
            margin: 10px 0;
            color: #007bff;
        }

        .service-card p {
            margin: 5px 0;
            color: #555;
        }

        .book-now {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            width: 100%;
        }

        .book-now:hover {
            background-color: #218 a45;
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
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container-fluid">
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

    <div class="container">
        <h1>Our Services</h1>
        <div class="filters">
            <div class="filter">
                <label for="sort">Sort by:</label>
                <select id="sort" onchange="location = this.value;">
                    <option value="?sort=price" <?= $sort == 'price' ? 'selected' : '' ?>>Price</option>
                    <option value="?sort=duration" <?= $sort == 'duration' ? 'selected' : '' ?>>Duration</option>
                </select>
            </div>
        </div>
        <div class="service-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="service-card">
                    <?php 
                    // Construct the local image path
                    $image_url = 'imgs/' . strtolower(str_replace(' ', '_', $row['service_name'])) . '.jpg'; 
                    ?>
                    <img src="<?= htmlspecialchars($image_url) ?>" alt="<?= htmlspecialchars($row['service_name']) ?>">
                    <h3><?= htmlspecialchars($row['service_name']) ?></h3>
                    <p>Price: ₱<?= number_format($row['price'], 2) ?></p>
                    <p>Duration: <?= htmlspecialchars($row['duration']) ?> hours</p>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <button class="book-now" onclick="window.location.href='book.php?service_id=<?= $row['service_id'] ?>'">Book Now</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>