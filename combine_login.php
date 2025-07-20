<?php
session_start();

$host = "localhost";
$port = "5432";
$dbname = "Village1";
$user = "postgres";
$password = "Whitedevil";

// Database Connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Database Connection Failed: " . pg_last_error());
}

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $aadhaar = trim($_POST["aadhaar"]);
    $password = trim($_POST["password"]);

    if (!ctype_digit($aadhaar)) {
        $error = "Invalid Aadhaar format!";
    } else {
        $query = "SELECT * FROM villagers WHERE aadhaar = $1";
        $result = pg_query_params($conn, $query, array($aadhaar));

        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);

            if (password_verify($password, $row["password"])) {
                $_SESSION['aadhaar'] = $aadhaar;
                $_SESSION['name'] = $row["name"];
                header("Location: index.php");
                exit();
            } else {
                $error = "Incorrect Password!";
            }
        } else {
            $error = "User Not Found!";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = trim($_POST["name"]);
    $aadhaar = trim($_POST["aadhaar"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $password = trim($_POST["password"]);

    if (!ctype_digit($aadhaar)) {
        $error = "Invalid Aadhaar format!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO villagers (name, aadhaar, phone, address, password) VALUES ($1, $2, $3, $4, $5)";
        $result = pg_query_params($conn, $query, array($name, $aadhaar, $phone, $address, $hashed_password));

        if ($result) {
            $success = "Registration successful! Please log in.";
        } else {
            $error = "Registration failed!";
        }
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

pg_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <style>
        body{
            background-color: #fdf0f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .container {
            display: flex;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .form-section {
            padding: 40px;
            width: 350px;
        }
        h2{
            text-align: center;
            font-weight: 800;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 19px;
        }
        button {
            padding: 10px;
            background-color: #4c438a;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 15px;
        }
        button:hover {
            background-color: #4c438a;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .img-section img {
            width: 350px;
            height: auto;
        }
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .tabs button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            background-color: #fdf0f9;
            color: #4c438a;
            cursor: pointer;
            border-radius: 15px;
        }
        .tabs button.active {
            background-color: #4c438a;
            color: #fdf0f9 ;
        }
        .admin-container {
            
            margin-top: 100px;
            background-color: #fdd0f9;
            padding: 20px;
            border-radius: 15px;
            width: 350px;
            border: #4c438a;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .admin-container a{
            text-decoration: none;
            font-weight: 600;
            a
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <div class="tabs">
                <button id="loginTab" class="active" onclick="showForm('login')">Login</button>
                <button id="registerTab" onclick="showForm('register')">Register</button>
            </div>

            <div id="loginForm">
                <h2>Villager Login</h2>
                <form method="POST" action="home2.php">
                    <input type="text" name="aadhaar" placeholder="Aadhaar Number" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
                <div class="signup">Don’t have an account? <a href="#" onclick="showForm('register')">Sign Up</a></div>
            </div>

            <div id="registerForm" style="display:none;">
                <h2>Villager Registration</h2>
                <form method="POST" action="combine_login.php">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="text" name="aadhaar" placeholder="Aadhaar Number" required>
                    <input type="text" name="phone" placeholder="Phone Number" required>
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                </form>
            </div>
        </div>
        <div class="img-section">
            <img src="img/login.jpeg" alt="Login Illustration">
        </div>
    </div>

    <div class="admin-container">
        
        <a href="admin.php">Click Here for Admin Log-in</a>
    </div>

    <script>
        function showForm(form) {
            document.getElementById('loginForm').style.display = form === 'login' ? 'block' : 'none';
            document.getElementById('registerForm').style.display = form === 'register' ? 'block' : 'none';
            document.getElementById('loginTab').classList.toggle('active', form === 'login');
            document.getElementById('registerTab').classList.toggle('active', form === 'register');
        }
    </script>
</body>
</html>
