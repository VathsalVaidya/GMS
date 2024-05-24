<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    $query = "SELECT fullname, status FROM members WHERE username='$username' AND password='$password'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $status = $row['status'];

        if ($status == 'active') {
            // Retrieve fullname
            $fullname = $row['fullname'];
            // Store username and fullname in session
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $fullname;
            
            // Redirect to the member dashboard or wherever you want
            header("Location: dashboard.php");
            exit();
        } elseif ($status == 'expired') {
            $error = "Membership expired. Please renew your membership.";
        } elseif ($status == 'Reg Pending') {
            $error = "Registration is pending approval. Please wait.";
        } else {
            $error = "Invalid user.";
        }
    } else {
        $error = "Invalid username or password.";
    }

    // Redirect with error message if any
    header("Location: member-login.php?error=" . urlencode($error));
    exit();
}
?>
