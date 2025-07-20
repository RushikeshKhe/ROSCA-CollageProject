<?php
include("villager_info.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_id = $_POST['group_id'];
    $villager_id = $_POST['villager_id'];

    $add_query = "INSERT INTO group_member (group_id, villager_id, join_date, status) VALUES ($1, $2, NOW(), 'Active')";
    $add_result = pg_query_params($conn, $add_query, array($group_id, $villager_id));

    if ($add_result) {
        echo "<script>alert('Member added successfully!'); window.location.href='group_details.php?group_id=$group_id';</script>";
    } else {
        die("Error adding member: " . pg_last_error($conn));
    }
}
?>
