<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
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
            background: url('img/gym4.jpg') no-repeat;
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

        /* New style for the member login option */
        .member-login {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .member-login a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .member-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <form action="admin/index.php" method="Post">
            <h1>GYM STATION</h1>
            <?php if (isset ($_GET['error'])) { ?>
                <p class="error">
                    <?php echo $_GET['error']; ?>
                </p>
            <?php } ?>
            <div class="input-box">
                <input type="text" name="user" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="pass" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <!-- Member login option -->
        <div class="member-login">
            <span>Are you a member?</span>
            <a href="members/member-login.php">Sign in as a member</a>
        </div>
    </div>
</body>

</html>
