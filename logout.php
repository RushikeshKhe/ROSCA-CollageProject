<?php
session_start();  // Start session
session_destroy(); // Destroy all session data
header("Location: combine_login.php"); // Redirect to login page
exit();
?>
