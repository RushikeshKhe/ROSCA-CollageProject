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

// Get group_id from request (default to 1 if not provided)
$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 1;

// Query to fetch Rosca Group, Cycle, and Members with User Details
$sql = "
SELECT 
    rg.group_id, rg.group_name, rg.start_date, rg.end_date,
    rc.cycle_id, rc.cycle_number, rc.winner_villager_id,
    gm.villager_id, v.villager_name, v.villager_contact
FROM rosca_groups rg
LEFT JOIN rosca_cycle rc ON rg.group_id = rc.ros_id
LEFT JOIN group_member gm ON rg.group_id = gm.group_id
LEFT JOIN villagers v ON gm.villager_id = v.villager_id
WHERE rg.group_id = $1";

$result = pg_query_params($conn, $sql, array($group_id));

if (!$result) {
    die("Query failed: " . pg_last_error());
}

// Fetch results
$rosca_details = pg_fetch_all($result) ?: [];

// Close DB Connection
pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosca Group Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4a261; color: white; }
        .container { max-width: 800px; margin: auto; }
    </style>
</head>
<body>

<div class="container">
    <h2>Rosca Group Details</h2>
    <form method="GET">
        <label for="groupSelect">Enter Group ID:</label>
        <input type="number" name="group_id" id="groupSelect" value="<?php echo htmlspecialchars($group_id); ?>">
        <button type="submit">Get Details</button>
    </form>

    <?php if (!empty($rosca_details)) { ?>
        <h3>Group Information</h3>
        <table>
            <tr><th>Group ID</th><td><?php echo htmlspecialchars($rosca_details[0]['group_id'] ?? "N/A"); ?></td></tr>
            <tr><th>Group Name</th><td><?php echo htmlspecialchars($rosca_details[0]['group_name'] ?? "N/A"); ?></td></tr>
            <tr><th>Start Date</th><td><?php echo htmlspecialchars($rosca_details[0]['start_date'] ?? "N/A"); ?></td></tr>
            <tr><th>End Date</th><td><?php echo htmlspecialchars($rosca_details[0]['end_date'] ?? "N/A"); ?></td></tr>
        </table>

        <h3>Cycle Details</h3>
        <table>
            <tr><th>Cycle ID</th><td><?php echo htmlspecialchars($rosca_details[0]['cycle_id'] ?? "N/A"); ?></td></tr>
            <tr><th>Cycle Number</th><td><?php echo htmlspecialchars($rosca_details[0]['cycle_number'] ?? "N/A"); ?></td></tr>
            <tr><th>Winner Villager ID</th><td><?php echo htmlspecialchars($rosca_details[0]['winner_villager_id'] ?? "N/A"); ?></td></tr>
        </table>

        <h3>Group Members</h3>
        <table>
            <tr>
                <th>Villager ID</th>
                <th>Name</th>
                <th>Contact</th>
            </tr>
            <?php foreach ($rosca_details as $member) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($member['villager_id'] ?? "N/A"); ?></td>
                    <td><?php echo htmlspecialchars($member['villager_name'] ?? "N/A"); ?></td>
                    <td><?php echo htmlspecialchars($member['villager_contact'] ?? "N/A"); ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No data found for this group.</p>
    <?php } ?>
</div>

</body>
</html>
