<?php
include 'db_connect.php';
$group = null;
$group_id = null;
$error_message = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = trim($_POST['group_input']);

    if (is_numeric($input)) {
        $query = "SELECT * FROM rosca_groups WHERE group_id = $1";
        $params = array($input);
    } else {
        $query = "SELECT * FROM rosca_groups WHERE group_name ILIKE $1";
        $params = array('%' . $input . '%');
    }

    $result = pg_query_params($conn, $query, $params);

    if ($result && pg_num_rows($result) > 0) {
        $group = pg_fetch_assoc($result);
        $group_id = $group['group_id'];
    } else {
        $error_message = "No matching group found. Please try again.";
    }
}

// Fetch Group Members using Aadhaar
if ($group_id) {
    $members_query = "SELECT v.villager_id, v.name 
                       FROM group_member gm
                       JOIN villagers v ON gm.villager_id = v.villager_id
                       WHERE gm.group_id = $1";

    $members_result = pg_query_params($conn, $members_query, array($group_id));

    // Fetch ROSCA Cycles
    $cycle_query = "SELECT rc.cycle_number AS cycle_no, v.name AS beneficiary_name
                     FROM rosca_cycle rc 
                     JOIN villagers v ON rc.winner_villager_id = v.villager_id 
                     WHERE rc.ros_id = $1 
                     ORDER BY rc.cycle_number";

    $cycle_result = pg_query_params($conn, $cycle_query, array($group_id));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Find Group Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dff6ff;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            width: 80%;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1, h2, h3 {
            color: #e91e63;
        }
        .block {
            background-color: #fce4ec;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #e91e63;
            color: white;
        }
        .button {
            display: inline-block;
            background-color: #e91e63;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #d81b60;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Find Group Details</h1>
        <form method="POST">
            <label>Enter Group Name or Group ID:</label>
            <input type="text" name="group_input" required>
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($error_message)) { ?>
            <p style="color: red;"> <?= htmlspecialchars($error_message) ?> </p>
        <?php } ?>

        <?php if ($group): ?>
            <div class="block">
                <h2>Group Details</h2>
                <p><strong>Group Name:</strong> <?= htmlspecialchars($group['group_name']) ?></p>
                <p><strong>Start Date:</strong> <?= htmlspecialchars($group['start_date']) ?></p>
                <p><strong>End Date:</strong> <?= htmlspecialchars($group['end_date']) ?></p>
                <p><strong>Amount:</strong> ₹<?= htmlspecialchars($group['amount']) ?></p>
                <p><strong>Admin Aadhaar:</strong> <?= htmlspecialchars($group['admin_aadhaar']) ?></p>
            </div>

            <div class="block">
                <h2>Members</h2>
                <?php if ($members_result && pg_num_rows($members_result) > 0) { ?>
                    <ul>
                        <?php while ($member = pg_fetch_assoc($members_result)) { ?>
                            <li><?= htmlspecialchars($member['name']) ?> (Villager ID: <?= htmlspecialchars($member['villager_id']) ?>)</li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <p>No members found in this group.</p>
                <?php } ?>
            </div>

            <div class="block">
                <h2>ROSCA Cycles</h2>
                <?php if ($cycle_result && pg_num_rows($cycle_result) > 0) { ?>
                    <table>
                        <tr>
                            <th>Cycle No</th>
                            <th>Beneficiary Name</th>
                        </tr>
                        <?php while ($cycle = pg_fetch_assoc($cycle_result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($cycle['cycle_no']) ?></td>
                                <td><?= htmlspecialchars($cycle['beneficiary_name']) ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } else { ?>
                    <p>No cycles have started for this group yet.</p>
                <?php } ?>
            </div>
        <?php endif; ?>

        <div class="container">
            <h3>Explore More</h3>
            <p>Click the button to create a new group</p>
            <a href="create_g.php" class="button">Go to Another Page</a>
        </div>
    </div>
</body>
</html>
