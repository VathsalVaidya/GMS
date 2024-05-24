<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if username is set in session
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in
    header('Location: member-login.php');
    exit();
}
?>
