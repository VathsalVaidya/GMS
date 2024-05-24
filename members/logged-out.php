<?php
// Start session
session_start();

// Destroy session
session_destroy();

// Prevent caching of the previous page
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Redirect to login page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <script>
        // Redirect to login page using JavaScript
        window.location.replace("member-login.php");
    </script>
</head>
<body>
    <!-- This page will automatically redirect to the login page -->
</body>
</html>
