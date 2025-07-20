<?php
$host = "localhost";
$port = "5432";
$dbname = "Village1";
$user = "postgres";
$password = "Whitedevil";

// Database Connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Services - Village Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f9f9ff;
            color: #333;
        }
        header {
            background-color: #fff;
            padding: 20px 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; /* Ensures it stays above other content */
        }
        nav a {
            margin-left: 20px;
            text-decoration: none;
            color: #555;
            font-weight: 600;
        }
        nav a:hover {
            color: #007bff;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            margin-top: 120px;
        }
        .section-title {
            background-color: #f3f8fb;
            font-size: 2.5rem;
            color: #4c438a;
            margin-bottom: 20px;
        }
        .about-section {
            display: flex;
            align-items: center;
            margin-bottom: 50px;
        }
        .about-section img {
            width: 40%;
            border-radius: 15px;
            margin-right: 20px;
        }
        .about-text {
            width: 60%;
        }
        .about-text h2 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .service-card {
            background-color: #fdf0f9;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .service-card:hover {
            transform: translateY(-10px);
        }
        .service-card h3 {
            color: #007bff;
        }
        .service-card p {
            margin: 10px 0;
            color: #666;
        }
        a.read-more {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4c438a;
            color: #fff;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
        }
        a.read-more:hover {
            background-color: rgb(219, 170, 216);
        }
        footer {
            background-color: #333;
            color: white;
            padding: 40px 10%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        footer h4 {
            color: #ff5e78;
        }
        footer a {
            color: #ccc;
            text-decoration: none;
        }
        footer a:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Village<span style="color: #ff5e78;">Management</span></div>
        <nav>
            <a href="about.php">About</a>
            <a href="home2.php">Home</a>
            <a href="combine_login.php">Log-In</a>
            <a href="#">Contact</a>
        </nav>
        <a href="profile2.php" class="read-more">Profile</a>
    </header>

    <div class="container">
        <div class="about-section">
            <img src="img/home.jpg" alt="Village Management Illustration">
            <div class="about-text">
                <h2>About</h2>
                <p>Welcome to our Village Management System, designed to enhance transparency, efficiency, and collaboration within villages. Our platform enables villagers to manage financial transactions, monitor expenses, and create groups for better community engagement. With a user-friendly interface and robust database management, we strive to bridge the gap between traditional management and digital innovation.</p>
            </div>
        </div>

        <h1 class="section-title">Our Services</h1>
        <div class="services">
            <div class="service-card">
                <h3>Village Transaction</h3>
                <p>Connecting basic money management activities in the village to bring transparency.</p>
                <a href="col_exp.php" class="read-more">Read More</a>
            </div>
            <div class="service-card">
                <h3>Villager Information Management</h3>
                <p>Store and manage all villager information efficiently using our system.</p>
                <a href="villager_info.php" class="read-more">Read More</a>
            </div>
            <div class="service-card">
                <h3>Group Creation</h3>
                <p>Create and manage groups to streamline community management and activities.</p>
                <a href="f_group.php" class="read-more">Read More</a>
            </div>
        </div>
    </div>

    <footer>
        <div>
            <h4>Village Management</h4>
            <p>Building transparency and efficiency in village management.</p>
        </div>
        <div>
            <h4>Company</h4>
            <a href="about.php">About Us</a><br>
            <a href="#">Services</a><br>
            <a href="about.php">Projects</a><br>
            <a href="#">Contact</a>
        </div>
        <div>
            <h4>Support</h4>
            <a href="#">FAQ</a><br>
            <a href="#">Help Center</a><br>
            <a href="#">Privacy Policy</a><br>
            <a href="#">Terms of Service</a>
        </div>
        <div>
            <h4>Contact Us</h4>
            <p>Email: support@villagemanagement.com</p>
            <p>Phone: +1 (234) 567-8900</p>
            <p>Address: 123 Village Rd, Hometown, Country</p>
        </div>
    </footer>
</body>
</html>
