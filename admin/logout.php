<?php
    // Start session
    session_start();
    
    // Destroy session
    session_destroy();
    
    // Redirect to login page
    header("Location: ../admin-login.php");
    exit;
?>
