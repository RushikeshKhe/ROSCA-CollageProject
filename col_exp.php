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

// Fetch Collection Data
$collection_query = "SELECT * FROM transaction WHERE type = 'collection' ORDER BY trans_date DESC";
$collection_result = pg_query($conn, $collection_query);

// Fetch Expense Data
$expense_query = "SELECT * FROM transaction WHERE type = 'expense' ORDER BY trans_date DESC";
$expense_result = pg_query($conn, $expense_query);

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection & Expense</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f4f4; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background-color: #2c3e50; padding: 15px 20px; }
        .nav-links { list-style: none; display: flex; }
        .nav-links li { margin: 0 15px; }
        .nav-links a { text-decoration: none; color: white; font-size: 18px; transition: 0.3s; }
        .nav-links a:hover { color: #f1c40f; }
        .profile { width: 40px; height: 40px; border-radius: 50%; background-color: white; cursor: pointer; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); text-align: center; }
        .tabs { display: flex; justify-content: center; margin-bottom: 20px; }
        .tab-btn { padding: 10px 20px; margin: 0 10px; border: none; background: #2c3e50; color: white; cursor: pointer; border-radius: 5px; transition: 0.3s; }
        .tab-btn:hover { background: #f1c40f; }
        .content { display: none; }
        .active { display: block; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: center; }
        th { background: #2c3e50; color: white; }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="combine_login.php">Login</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="home.html">Home</a></li>
        </ul>
        <img src="profile.png" alt="Profile" class="profile">
    </nav>

    <!-- Collection & Expense Section -->
    <div class="container">
        <h2>Collection & Expense Details</h2>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn" onclick="showTab('collection')">Collection</button>
            <button class="tab-btn" onclick="showTab('expense')">Expense</button>
        </div>

        <!-- Collection Section -->
        <div id="collection" class="content active">
            <h3>Collection Details</h3>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Transaction Id</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
                <?php while ($row = pg_fetch_assoc($collection_result)) { ?>
                    <tr>
                        <td><?php echo $row['trans_date']; ?></td>
                        <td><?php echo $row['trans_id']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>₹<?php echo $row['amount']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Expense Section -->
        <div id="expense" class="content">
            <h3>Expense Details</h3>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Transaction ID</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
                <?php while ($row = pg_fetch_assoc($expense_result)) { ?>
                    <tr>
                        <td><?php echo $row['trans_date']; ?></td>
                        <td><?php echo $row['trans_id']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>₹<?php echo $row['amount']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
        }
    </script>

</body>
</html>
