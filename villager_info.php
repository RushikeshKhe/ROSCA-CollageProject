<?php
$host = "localhost";
$port = "5432";
$dbname = "Village1";
$user = "postgres";
$password = "Whitedevil";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Fetch all villagers' data
$query = "SELECT name, aadhaar, phone, address FROM villagers";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error fetching villagers' data: " . pg_last_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villagers Info</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .navbar { background: #333; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; padding: 10px; }
        .container { width: 80%; margin: 20px auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #333; color: white; }
    </style>
</head>
<body>

    <div class="navbar">
        <div>
            <a href="home2.php">Home</a>
            <a href="combine_login.php">Login</a>
            <a href="about.php">About</a>
        </div>
        <div>
            <a href="profile2.php">Profile</a>
        </div>
    </div>

    <div class="container">
        <h2>Villagers Information</h2>
        <table>
            <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Aadhaar</th>
                <th>Phone</th>
                <th>Address</th>
            </tr>
            <?php 
            $srno = 1; // Initialize serial number
            while ($row = pg_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $srno++; ?></td>
                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["aadhaar"]); ?></td>
                    <td><?php echo htmlspecialchars($row["phone"]); ?></td>
                    <td><?php echo htmlspecialchars($row["address"]); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>

<?php pg_close($conn); ?>
