<?php
    // Start session
    session_start();

    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    header('location: member-login.php');

    ?>
