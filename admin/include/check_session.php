<?php
// Check if a session is not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the admin is not logged in, redirect to login page
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin-login.php");
    exit();
}
?>
