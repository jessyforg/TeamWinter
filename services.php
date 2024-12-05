<?php
// Database connection
$host = 'localhost'; // Change if necessary
$db = 'booking_system';
$user = 'root'; // Change if necessary
$pass = ''; // Change if necessary

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Determine sorting criteria
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price'; // Default to sorting by price
$order_by = 'price'; // Default sorting

switch ($sort) {
    case 'price':
        $order_by = 'price';
        break;
    case 'duration':
        $order_by = 'duration';
        break;
    default:
        $order_by = 'price'; // Fallback to price if invalid
}

// Fetch services from the database with sorting
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
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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
            width: 100%; /* Make the image take the full width of the card */
            height: 200px; /* Set a fixed height for uniformity */
            object-fit: cover; /* Ensure the image covers the area without distortion */
            border-radius: 8px; /* Keep the rounded corners */
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
    </style>
</head>
<body>
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
                    <button class="book-now" onclick="window.location.href='booking_page.php?service_id=<?= $row['service_id'] ?>'">Book Now</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>