<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user']) && isset($_POST['pass'])) {
        $username = $_POST['user'];
        $password = $_POST['pass'];

        $query = "SELECT fullname, phone, status FROM members WHERE username='$username' AND password='$password'";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $status = $row['status'];

            if ($status == 'active') {
                // Retrieve fullname and phone
                $fullname = $row['fullname'];
                $phone = $row['phone'];

                // Store username, fullname, and phone in session
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['phone'] = $phone;

                // Redirect to the OTP process page
                header("Location: otp-process.php");
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
}
?>
