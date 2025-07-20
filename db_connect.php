<?php
// Database Connection Details
$host = "localhost";
$port = "5432";
$dbname = "Village1";
$user = "postgres";
$password = "Whitedevil";

// Create Connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Check Connection
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>
