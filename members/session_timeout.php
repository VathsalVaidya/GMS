<?php
// Start session
session_start();

// Function to check session expiration
function check_session_timeout($timeout_duration = 1800) {
    // Check if last activity timestamp is set
    if (isset($_SESSION['last_activity'])) {
        // Get current timestamp
        $current_time = time();
        // Calculate time difference in seconds
        $elapsed_time = $current_time - $_SESSION['last_activity'];

        // Check if elapsed time exceeds the timeout duration
        if ($elapsed_time > $timeout_duration) {
            // Session expired, destroy session and redirect to login page
            session_unset();
            session_destroy();
            header("Location: member-login.php");
            exit();
        }
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
}

// Call the function to check session expiration
check_session_timeout();
?>
