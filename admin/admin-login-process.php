<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    $query = "SELECT username FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];

        // Store username in session
        $_SESSION['admin_username'] = $username;
            
        // Redirect to the admin dashboard or wherever you want
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
        // Redirect with error message
        header("Location: admin-login.php?error=" . urlencode($error));
        exit();
    }
}
?>
