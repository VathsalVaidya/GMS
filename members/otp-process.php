<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: member-login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['otp'])) {
        $otp_entered = $_POST['otp'];
        $username = $_SESSION['username'];

        // Retrieve verification code from members table
        $query = "SELECT verification_code FROM members WHERE username='$username'";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $verification_code = $row['verification_code'];

            // Compare entered OTP with stored verification code
            if ($otp_entered == $verification_code) {
                // OTP matched, redirect to member dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid OTP. Please try again.";
            }
        } else {
            $error = "No verification code found for this user.";
        }
    }

    // Resend OTP email
    if (isset($_POST['resend'])) {
        // Generate a new verification code
        $new_verification_code = rand(100000, 999999);
        
        // Update verification code in the database
        $update_query = "UPDATE members SET verification_code='$new_verification_code' WHERE username='$username'";
        mysqli_query($connection, $update_query);

        // Send verification email with new OTP
        // Call your sendVerificationEmail function here passing the user's email and new verification code
        // Example: sendVerificationEmail($user_email, $new_verification_code);

        // Provide feedback to the user
        $message = "New OTP sent successfully.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        /* Your CSS styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('../img/gym4.jpg') no-repeat;
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 400px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .wrapper h1 {
            font-size: 28px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .input-box input {
            width: 100%;
            height: 40px;
            background: transparent;
            border: 2px solid #ccc;
            border-radius: 20px;
            padding: 0 15px;
            font-size: 16px;
            color: #333;
            outline: none;
        }

        .input-box input::placeholder {
            color: #999;
        }

        .input-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .btn {
            width: 100%;
            height: 40px;
            background: #ffc107;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #ffca2c;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Verify Your Email</h1>
        <form action="" method="POST">
            <?php if (isset($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
            <div class="input-box">
                <input type="text" name="otp" placeholder="Enter OTP" required>
                <i class='bx bxs-key'></i>
            </div>
            <button type="submit" class="btn">Verify</button>
        </form>
    </div>
</body>
</html>
