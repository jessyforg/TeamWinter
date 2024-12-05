<?php
// Database connection
$servername = "localhost";
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "booking_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch popular services
$services_sql = "SELECT * FROM Services LIMIT 3";
$services_result = $conn->query($services_sql);

// Fetch testimonials
$reviews_sql = "SELECT * FROM Reviews LIMIT 3";
$reviews_result = $conn->query($reviews_sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .hero {
            background-image: url('imgs/bg.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            position: relative;
        }
        
        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero .card {
            z-index: 2;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        
        .hero .btn {
            margin: 10px;
            padding: 10px 30px;
            border-radius: 50px;
            background-color: #008CBA;
            color: white;
            text-transform: uppercase;
        }

        .hero .btn:hover {
            background-color: #005f72;
        }

        .service-card img {
            height: 200px;
            object-fit: cover;
        }

        .service-card {
            transition: transform 0.3s, box-shadow 0.3s; / Smooth transition for transform and box-shadow /
        }

        .service-card:hover {
            transform: scale(1.05); / Slightly scale up the card /
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4); / Increase shadow on hover */
        }

        .service-card .card-body {
            padding: 20px;
        }
        
        .testimonial .card-body {
            padding: 20px;
        }

        .stars {
            color: gold;
            font-size: 1.5rem;
        }

        .stars .fa-star {
            color: gold; 
        }

        .stars .fa-star-half-alt {
            color: gold; 
        }

        .stars .fa-star-o {
            color: lightgray;
        }

        .cta {
            background-color: #008CBA;
            color: white;
            text-align: center;
            padding: 40px 0;
        }
        .cta .btn {
            background-color: #ffffff;
            color: #008CBA;
            font-size: 1.2rem;
            text-transform: uppercase;
        }

        .cta .btn:hover {
            background-color: #005f72;
            color: white;
        }

        .stars{
            color: #f39c12;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="hero">
    <div class="card">
        <h1>Your Wellness Journey Starts Here</h1>
        <p>Transform your life with our personalized services</p>
        <a href="services.php" class="btn">View Services</a>
        <a href="book.php" class="btn">Book Now</a>
    </div>
</div>

<div class="container mt-5" id="services">
    <h2 class="text-center mb-4">Our Popular Services</h2>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card service-card">
                <img src="imgs/whole body massage.jpg" class="card-img-top" alt="Whole Body Massage"> <!-- Replace with actual image -->
                <div class="card-body">
                    <h5 class="card-title">Whole Body Massage</h5>
                    <p class="card-text">Indulge in a relaxing full-body massage designed to relieve stress, improve circulation, and promote overall well-being.</p>
                    <p><strong>Price: ₱1250.00</strong></p>
                    <a href="book.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card service-card">
                <img src="imgs/facial treatment.jpg" class="card-img-top" alt="Facial Treatment"> <!-- Replace with actual image -->
                <div class="card-body">
                    <h5 class="card-title">Facial Treatment</h5>
                    <p class="card-text">Rejuvenate your skin with our personalized facial treatments that cleanse, exfoliate, and hydrate for a radiant and healthy glow. Perfect for all skin types.</p>
                    <p><strong>Price: ₱1000.00</strong></p>
                    <a href="book.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card service-card">
                <img src="imgs/spa manipedi.jpg" class="card-img-top" alt="Spa ManiPedi"> <!-- Replace with actual image -->
                <div class="card-body">
                    <h5 class="card-title">Spa ManiPedi</h5>
                    <p class="card-text">Treat your feet and hands with a soothing spa pedicure that includes exfoliation, massage, and nail care.</p>
                    <p><strong>Price: ₱750.00</strong></p>
                    <a href="book.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card service-card">
                <img src="imgs/aroma therapy.jpg" class="card-img-top" alt="Aroma Therapy"> <!-- Replace with actual image -->
                <div class="card-body">
                    <h5 class="card-title">Aroma Therapy</h5>
                    <p class="card-text">Relax and unwind with our therapeutic massage services that relieve tension and promote overall wellness.</p>
                    <p><strong>Price: ₱2000.00</strong></p>
                    <a href="book.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card service-card">
                <img src="imgs/hair treatment.jpg" class="card-img-top" alt="Hair Treatment"> <!-- Replace with actual image -->
                <div class="card-body">
                    <h5 class="card-title">Hair Treatment</h5>
                    <p class="card-text">Transform your hair with nourishing treatments that restore shine, strength, and manageability. Options include keratin, hot oil, and scalp detox.</p>
                    <p><strong>Price: ₱1500.00</strong></p>
                    <a href="book.php" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="testimonial" id="testimonials">
    <div class="container">
        <h2 class="text-center mb-4">What Our Customers Say</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="imgs/john_doe.jpg" alt="John Doe" class="rounded-circle" width="50" height="50"> <!-- Profile image -->
                            <h5 class="card-title ms-3">John Doe's Review</h5>
                        </div>
                        <p class="card-text">"This is by far the best wellness service I've ever experienced. The whole body massage was incredibly relaxing and rejuvenating. Highly recommend!"</p>
                        <p><strong>Rating: </strong>
                            <span class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span> <!-- Displaying 5 gold stars -->
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="imgs/jane_smith.jpg" alt="Jane Smith" class="rounded-circle" width="50" height="50">
                            <h5 class="card-title ms-3">Jane Smith's Review</h5>
                        </div>
                        <p class="card-text">"The facial treatment was absolutely amazing. My skin has never felt so refreshed. The therapist was professional and attentive. I will definitely come back."</p>
                        <p><strong>Rating: </strong>
                            <span class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i></span> <!-- Displaying 4.5 stars -->
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="imgs/susan_clark.jpg" alt="Susan Clark" class="rounded-circle" width="50" height="50">
                            <h5 class="card-title ms-3">Susan Clark's Review</h5>
                        </div>
                        <p class="card-text">"I had an amazing experience at this wellness center. The service was top-notch, and I felt completely pampered. Definitely coming back."</p>
                        <p><strong>Rating: </strong>
                            <span class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span> <!-- Displaying 5 gold stars -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cta" id="book">
    <h2>Ready to Start Your Wellness Journey?</h2>
    <p>Sign up now and book your first session with one of our expert therapists.</p>
    <a href="signup.php" class="btn btn-lg">Create an Account</a>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>