<?php
include '../config.php';
require 'C:/Users/vinay/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function generateVerificationCode() {
    // Generate a random verification code
    return rand(100000, 999999);
}

function sendVerificationEmail($email, $verificationCode) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'vathsalvaidya@gmail.com'; // SMTP username
        $mail->Password = 'skwqsbrahbnqwgux'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // SMTP port

        //Recipients
        $mail->setFrom('vathsalvaidya@gmail.com', 'Vathsal Vaidya');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verification Code';
        $mail->Body = 'Your verification code is: ' . $verificationCode;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function validateNepaliPhoneNumber($number) {
    $number = preg_replace('/[^0-9]/', '', $number);
    if (preg_match('/^(\+977)?[9][6-8]\d{8}$/', $number)) {
        return true;
    } else {
        return false;
    }
}

function validateFullName($fullname) {
    // Perform validation logic here
    // For example, you can use regular expressions to validate the full name format
    return preg_match('/^[a-zA-Z\s]+$/', $fullname);
}

function validatePassword($password) {
    // Password must be at least 6 characters long and contain both letters and numbers
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password);
}

function validateEmail($email) {
    // Validate email address format
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


if (isset($_POST["submit"])) {
    // Check if the connection is established
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $password = $_POST["password"]; // Plain text password
    $dob = $_POST["dob"];
    $phone = $_POST["phone"];
    $email = $_POST["email"]; // New email field
    $address = $_POST["address"];
    $gender = $_POST["gender"];
    $plan = $_POST["plan"];
    $service = $_POST["service"];

    // Set default status to 'Reg Pending'
    $status = 'Reg Pending';

    // Generate verification code
    $verificationCode = generateVerificationCode();

    // Send verification email
    if (!sendVerificationEmail($email, $verificationCode)) {
        $error = "Failed to send verification email.";
    } else {
        // Validate Full Name
        if (!validateFullName($fullname)) {
            $error = "Invalid full name format. Please enter alphabetic characters and spaces only.";
        }
        // Validate Password
        elseif (!validatePassword($password)) {
            $error = "Invalid password format. Password must be at least 6 characters long and contain both letters and numbers.";
        }
        // Validate phone number
        elseif (!validateNepaliPhoneNumber($phone)) {
            $error = "Invalid phone number format. Please enter a valid Nepali phone number.";
        }
        // Validate email
        elseif (!validateEmail($email)) {
            $error = "Invalid email address format. Please enter a valid email address.";
        } else {
            // Check if username, phone number, or email already exist
            $check_username_query = "SELECT * FROM members WHERE username='$username'";
            $check_phone_query = "SELECT * FROM members WHERE phone='$phone'";
            $check_email_query = "SELECT * FROM members WHERE email='$email'";
            $username_result = mysqli_query($connection, $check_username_query);
            $phone_result = mysqli_query($connection, $check_phone_query);
            $email_result = mysqli_query($connection, $check_email_query);

            if (mysqli_num_rows($username_result) > 0) {
                // Username already exists
                $error = "Username already registered. Please choose a different username.";
            } elseif (mysqli_num_rows($phone_result) > 0) {
                // Phone number already exists
                $error = "Phone number already registered.";
            } elseif (mysqli_num_rows($email_result) > 0) {
                // Email already exists
                $error = "Email address already registered.";
            } else {
                // Store the plain password directly
                $plain_password = $password;

                // Prepare SQL statement to insert data into the database
                $ins = "INSERT INTO members (fullname, username, password, dob, phone, email, address, gender, plan, service, status, verification_code) 
                        VALUES ('$fullname','$username','$plain_password', '$dob', '$phone', '$email', '$address','$gender','$plan','$service', '$status', '$verificationCode')";

                // Execute the SQL query
                $query = mysqli_query($connection, $ins);

                // Check if the query was successful
                if ($query) {
                    // Display success message
                    $message = "Account registered successfully. Please wait for registration approval.";
                    // Redirect to another page to prevent form resubmission
                    header("Location: registration-success.php");
                    exit();
                } else {
                    // Display an error message and the SQL error, if any
                    $error = "Failed to insert data: " . mysqli_error($connection);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration</title>
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

        .input-box select {
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

        .message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .member-registration {
            text-align: center;
            margin-top: 20px;
        }

        .member-registration a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .member-registration a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>GYM STATION</h1>
        <form method="POST">
            <?php if (isset($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
            <?php if (isset($message)) { ?>
                <p class="message"><?php echo $message; ?></p>
            <?php } ?>
            <div class="input-box">
                <input type="text" name="fullname" placeholder="Fullname" required>
            </div>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
                <input type="date" name="dob" placeholder="Date of Birth" required>
            </div>
            <div class="input-box">
                <input type="tel" name="phone" placeholder="Phone Number" required>
                <i class='bx bxs-phone'></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email Address" required> <!-- Email input field -->
                <i class='bx bx-envelope'></i> 
            </div>
            <div class="input-box">
                <input type="text" name="address" placeholder="Address" required>
                <i class='bx bxs-map'></i>
            </div>
            <div class="input-box">
                <select name="gender" required>
                    <option value="" disabled selected>Choose Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <i class='bx bx-select-multiple'></i>
            </div>
            <div class="input-box">
                <select name="plan" required>
                    <option value="" disabled selected>Choose Plan</option>
                    <option value="basic">Basic</option>
                    <option value="standard">Standard</option>
                    <option value="premium">Premium</option>
                </select>
                <i class='bx bx-select-multiple'></i>
            </div>
            <div class="input-box">
                <select name="service" required>
                    <option value="" disabled selected>Choose Service</option>
                    <option value="personal-trainer">Personal Trainer</option>
                    <option value="group-classes">Group Classes</option>
                    <option value="nutrition-counseling">Nutrition Counseling</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn">Register</button>
        </form>
        <div class="member-registration">
            <span>Already a member? <a href="member-login.php">Sign in here</a></span>
        </div>
    </div>
</body>
</html>

