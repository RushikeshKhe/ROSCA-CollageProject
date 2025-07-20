<?php
session_start();
include 'db_connect.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['villager_id'])) {
    die("You must be logged in to view this page.");
}

$villager_id = $_SESSION['villager_id']; // Get logged-in user's ID

// Fetch group details the user belongs to
$query = "SELECT g.group_id, g.group_name, g.start_date, g.end_date, g.status, g.amount
          FROM rosca_groups g
          JOIN group_member gm ON g.group_id = gm.group_id
          WHERE gm.villager_id = $1";

$result = pg_query_params($conn, $query, array($villager_id));
if (!$result) {
    die("Error fetching groups: " . pg_last_error($conn));
}

echo "<h2>Your Groups</h2>";
while ($row = pg_fetch_assoc($result)) {
    echo "<h3>" . htmlspecialchars($row['group_name']) . "</h3>";
    echo "Start Date: " . $row['start_date'] . "<br>";
    echo "End Date: " . $row['end_date'] . "<br>";
    echo "Status: " . $row['status'] . "<br>";
    echo "Amount: " . $row['amount'] . "<br>";

    // Fetch members of this group
    $group_id = $row['group_id'];
    $member_query = "SELECT v.villager_id, v.name FROM villagers v
                     JOIN group_member gm ON v.villager_id = gm.villager_id
                     WHERE gm.group_id = $1";
    $member_result = pg_query_params($conn, $member_query, array($group_id));

    echo "<h4>Group Members:</h4><ul>";
    while ($member = pg_fetch_assoc($member_result)) {
        echo "<li>" . htmlspecialchars($member['name']) . "</li>";
    }
    echo "</ul>";

    // Fetch cycle details
    $cycle_query = "SELECT cycle_number, winner_villager_id FROM rosca_cycle WHERE ros_id = $1";
    $cycle_result = pg_query_params($conn, $cycle_query, array($group_id));

    echo "<h4>Cycle Details:</h4><ul>";
    while ($cycle = pg_fetch_assoc($cycle_result)) {
        echo "<li>Cycle " . $cycle['cycle_number'] . " - Winner ID: " . $cycle['winner_villager_id'] . "</li>";
    }
    echo "</ul><hr>";
}

pg_close($conn);
?>
