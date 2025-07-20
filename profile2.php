<?php
session_start();

// Debug: Remove this in production
// var_dump($_SESSION);

// Check if user is logged in
if (!isset($_SESSION['aadhaar'])) {
    header("Location: combine_login.php");
    exit();
}

// Database connection
$host = "localhost";
$port = "5432";
$dbname = "Village1";
$user = "postgres";
$password = "Whitedevil";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Fetch user details securely using prepared statements
$aadhaar = $_SESSION['aadhaar'];
$query = "SELECT * FROM villagers WHERE aadhaar = $1";
$result = pg_query_params($conn, $query, [$aadhaar]);

if (!$result || pg_num_rows($result) == 0) {
    die("User data not found.");
}

$user = pg_fetch_assoc($result);
pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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
            position: fixed;
            top: 0;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            z-index: 1000;
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
            margin-top: 120px;
            max-width: 800px;
            margin: 120px auto 50px auto;
            padding: 20px;
        }
        .profile-card {
            background: linear-gradient(135deg, #fdf0f9, rgb(211, 123, 206));
            color: #493e8c;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .profile-card h2 {
            margin-bottom: 20px;
        }
        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .info-block {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 18px;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            margin-top: 20px;
        }
        .logout-btn:hover {
            background: #c0392b;
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
    <!-- Navigation Bar -->
    <header>
        <div class="logo">Village<span style="color: #ff5e78;">Management</span></div>
        <nav>
            <a href="home2.php">Home</a>
            <a href="about.php">About</a>
            <a href="collection_expense.php">Collection & Expense</a>
            <a href="profile2.php" class="read-more">Profile</a>
        </nav>
    </header>

    <!-- Profile Container -->
    <div class="container">
        <div class="profile-card">
            <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
            <div class="profile-info">
                <div class="info-block"><strong>Name:</strong> <span><?php echo htmlspecialchars($user['name']); ?></span></div>
                <div class="info-block"><strong>Aadhaar Number:</strong> <span><?php echo htmlspecialchars($user['aadhaar']); ?></span></div>
                <div class="info-block"><strong>Phone:</strong> <span><?php echo htmlspecialchars($user['phone']); ?></span></div>
                <div class="info-block"><strong>Address:</strong> <span><?php echo htmlspecialchars($user['address']); ?></span></div>
            </div>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div>
            <h4>Village Management</h4>
            <p>Building transparency and efficiency in village management.</p>
        </div>
        <div>
            <h4>Company</h4>
            <a href="#">About Us</a><br>
            <a href="#">Services</a><br>
            <a href="#">Projects</a><br>
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
