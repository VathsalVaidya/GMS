<?php
session_start();
include "../config.php";

if (isset($_POST['user']) && isset($_POST['pass'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['user']);
    $password = validate($_POST['pass']);

    if (empty($username)) {
        header("Location: admin-login.php?error=Username is required");
        exit();
    } elseif (empty($password)) {
        header("Location: admin-login.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT username FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['admin_username'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: admin-login.php?error=Incorrect Username or Password");
            exit();
        }
    }
} else {
    header("Location: admin-login.php");
    exit();
}
?>
