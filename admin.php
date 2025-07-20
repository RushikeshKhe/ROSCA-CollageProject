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
pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login and Registration</title>
    <style>
        body{
            background-color: #f9f7e9;
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
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 19px;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 15px;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .tabs button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 15px;
        }
        .tabs button.active {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <div class="tabs">
                <button id="loginTab" class="active" onclick="showForm('login')">Admin Login</button>
                <button id="registerTab" onclick="showForm('register')">Admin Register</button>
            </div>

            <div id="loginForm">
                <h2>Admin Login</h2>
                <form method="POST" action="admin_home.php">
                    <input type="text" name="username" placeholder="Admin Username" required>
                    <input type="password" name="password" placeholder="Admin Password" required>
                    <button type="submit">Login</button>
                </form>
            </div>

            <div id="registerForm" style="display:none;">
                <h2>Admin Registration</h2>
                <form method="POST" action="admin.php">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="text" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="text" name="contact" placeholder="Contact Number" required>
                    <input type="text" name="aadhaar" placeholder="Aadhaar Number" required>
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
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
