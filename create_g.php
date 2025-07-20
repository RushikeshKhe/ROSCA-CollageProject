<?php
include 'db_connect.php';
session_start();

// Check if Aadhaar is set in session
if (!isset($_SESSION['aadhaar'])) {
    die("Error: Aadhaar not found. Please log in.");
}
$admin_aadhaar = $_SESSION['aadhaar'];

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $group_name = htmlspecialchars(trim($_POST['group_name']));
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = htmlspecialchars(trim($_POST['status']));
    $amount = floatval($_POST['amount']);

    // Validate input
    if (!empty($group_name) && !empty($start_date) && !empty($end_date) && !empty($status) && $amount > 0) {
        // Prepare SQL query using parameterized values
        $query = "INSERT INTO rosca_groups (group_name, start_date, end_date, status, amount, admin_aadhaar) 
                  VALUES ($1, $2, $3, $4, $5, $6)";
        
        $result = pg_query_params($conn, $query, array($group_name, $start_date, $end_date, $status, $amount, $admin_aadhaar));

        if ($result) {
            echo "Group created successfully!";
        } else {
            error_log("Error: " . pg_last_error($conn));
            echo "An error occurred while creating the group.";
        }
    } else {
        echo "All fields are required, and the amount must be greater than zero.";
    }
}
?>

<!-- HTML Form for Group Creation -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Group - ROSCA</title>
    <style>
        body {
            background-color: #F3F0FF;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 15px 50px;
            border-bottom: 2px solid #E0E0E0;
        }

        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        nav li {
            margin: 0 15px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #007bff;
        }

        .profile a {
            background-color: #6C63FF;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .profile a:hover {
            background-color: #5145CD;
        }

        form {
            background-color: #ffffff;
            padding: 50px;
            border-radius: 15px;
            max-width: 400px;
            height: auto;
            margin: 80px auto 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], input[type="date"], input[type="number"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 25px;
        }

        button {
            background-color: #6C63FF;
            color: white;
            padding: 14px;
            margin: 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #5145CD;
        }

        .form-link {
            text-align: center;
            margin-top: 20px;
        }

        .form-link a {
            text-decoration: none;
            color: #6C63FF;
            font-weight: bold;
        }

        .form-link a:hover {
            text-decoration: underline;
        }
        button a{
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>

    <nav>
        <ul>
            <li><a href="combine_login.php">Log In</a></li>
            <li><a href="combine_login.php">Register</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="home2.php">Home</a></li>
        </ul>
        <div class="profile">
            <a href="profile.php">Profile</a>
        </div>
    </nav>

    <!-- Group Creation Form -->
    <form method="POST">
        <h2>Create a Group</h2>
        <label>Group Name:</label>
        <input type="text" name="group_name" required>

        <label>Start Date:</label>
        <input type="date" name="start_date" required>

        <label>End Date:</label>
        <input type="date" name="end_date" required>

        <label>Status:</label>
        <input type="text" name="status" required>

        <label>Amount (₹):</label>
        <input type="number" name="amount" step="0.01" required min="0.01">

        <button type="submit">Create Group</button>
        
        <button ><a href="f_group.php">Look For Existing Groups</a></button>
    </form>

   
</body>
</html>

