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

// Fetch transactions
$default_transactions = pg_query($conn, "SELECT * FROM transaction");
$transactions = pg_fetch_all($default_transactions);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['villagerId'])) {
        // Add new transaction
        $villagerId = $_POST['villagerId'];
        $type = $_POST['type'];
        $amount = $_POST['amount'];
        $transDate = $_POST['transDate'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        $query = "INSERT INTO transaction (villager_id, type, amount, trans_date, description, status) 
                  VALUES ('$villagerId', '$type', '$amount', '$transDate', '$description', '$status')";
        pg_query($conn, $query);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['trans_id'])) {
        // Update transaction status and description
        $transId = $_POST['trans_id'];
        $status = $_POST['status'];
        $description = $_POST['description'];

        $query = "UPDATE transaction SET status='$status', description='$description' WHERE trans_id='$transId'";
        pg_query($conn, $query);
        echo "Done";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Transactions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>.tabs button {
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
        </style>    
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Admin - Manage Transactions</h2>
        
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Villager ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= $transaction['trans_id'] ?></td>
                        <td><?= $transaction['villager_id'] ?></td>
                        <td><?= $transaction['type'] ?></td>
                        <td><?= $transaction['amount'] ?></td>
                        <td><?= $transaction['trans_date'] ?></td>
                        <td>
                            <select class="form-control" id="status_<?= $transaction['trans_id'] ?>" onchange="updateStatus(<?= $transaction['trans_id'] ?>)">
                                <option value="pending" <?= $transaction['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Done" <?= $transaction['status'] === 'Done' ? 'selected' : '' ?>>Done</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" id="desc_<?= $transaction['trans_id'] ?>" class="form-control" placeholder="Enter description" value="<?= $transaction['description'] ?>">
                        </td>
                        <td>
                            <button class="btn btn-primary" onclick="updateStatus(<?= $transaction['trans_id'] ?>)">Update</button>
                            <button class="btn btn-danger" onclick="deleteRow(<?= $transaction['trans_id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Add Transaction</h4>
        <form method="POST">
            <div class="mb-2">
                <input type="text" name="villagerId" class="form-control" placeholder="Villager ID" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Type</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="typeToggle">
                    <label class="form-check-label" for="typeToggle" id="typeLabel">Collection</label>
                    <input type="hidden" name="type" id="typeValue" value="collection">
                </div>
            </div>
            <div class="mb-2">
                <input type="number" name="amount" class="form-control" placeholder="Amount" required>
            </div>
            <div class="mb-2">
                <input type="date" name="transDate" class="form-control" required>
            </div>
            <div class="mb-3">
                <input type="text" name="description" class="form-control" placeholder="Enter transaction description" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="pending">Pending</option>
                    <option value="Done">Done</option>
                </select>
            </div>
            <button type="submit" class="btn btn-Done">Add Transaction</button>
        </form>
    </div>

    <script>
        document.getElementById("typeToggle").addEventListener("change", function() {
            document.getElementById("typeLabel").innerText = this.checked ? "Expense" : "Collection";
            document.getElementById("typeValue").value = this.checked ? "expense" : "collection";
        });

        function updateStatus(transId) {
            let status = document.getElementById("status_" + transId).value;
            let description = document.getElementById("desc_" + transId).value;

            fetch("manage_transactions.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "trans_id=" + transId + "&status=" + status + "&description=" + encodeURIComponent(description)
            })
            .then(response => response.text())
            .then(data => alert("Status & Description updated Donefully!"))
            .catch(error => alert("Error updating data!"));
        }

        function deleteRow(transId) {
            if (confirm("Are you sure you want to delete this transaction?")) {
                fetch("delete_transaction.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "trans_id=" + transId
                }).then(response => response.text()).then(data => location.reload());
            }
        }
    </script>
</body>
</html>

<?php pg_close($conn); ?>
