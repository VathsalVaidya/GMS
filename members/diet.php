<?php
session_start();
include '../config.php';

function check_session_timeout($timeout_duration = 1800) {
    // Check if last activity timestamp is set
    if (isset($_SESSION['last_activity'])) {
        // Get current timestamp
        $current_time = time();
        // Calculate time difference in seconds
        $elapsed_time = $current_time - $_SESSION['last_activity'];

        // Check if elapsed time exceeds the timeout duration
        if ($elapsed_time > $timeout_duration) {
            // Session expired, destroy session and redirect to login page
            session_unset();
            session_destroy();
            header("Location: member-login.php");
            exit();
        }
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
}

// Call the function to check session expiration
check_session_timeout();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: member-login.php");
    exit();
}

// Initialize variables for form submission
$success_message = "";
$error_message = "";
$diet_plans = array(); // Initialize diet_plans array

// Fetch reminder count for the logged-in user
$username = $_SESSION['username'];
$query = "SELECT COUNT(*) AS reminder_count FROM members WHERE username=? AND reminder=1";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $reminder_count);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diet_plans = $_POST['diet_plan'];
    $username = $_SESSION['username'];

    foreach ($diet_plans as $diet_plan) {
        $diet_plan = mysqli_real_escape_string($connection, $diet_plan);

        // Insert each diet plan into the database
        $query = "INSERT INTO diet_plans (username, diet_plan) VALUES (?, ?)";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $diet_plan);
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Diet plans saved successfully!";
        } else {
            $error_message = "Error saving diet plans: " . mysqli_error($connection);
            break; // Exit loop on first error
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan a Diet</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .custom-card {
            max-width: 800px;
            margin: 0 auto;
        }
        
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboard-sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand ms-2 text-uppercase fw-bold" href="#">GYM STATION</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start sidebar-nav bg-dark" tabindex="-1" id="dashboard-sidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white">Menu</h5>
        </div>
        <div class="offcanvas-body p-0">
            <nav class="navbar-dark">
                <ul class="navbar-nav flex-column">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link px-3">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="notifications.php" class="nav-link px-3">
                            <i class="bi bi-bell me-2"></i>
                            Notifications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="reminders.php" class="nav-link px-3">
                            <i class="bi bi-calendar-check me-2"></i>
                            Reminders
                            <?php if ($reminder_count > 0) { ?>
                                <span class="badge bg-danger"><?php echo $reminder_count; ?></span>
                            <?php } ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="diet.php" class="nav-link px-3 active">
                            <i class="bi bi-nutrition me-2"></i>
                            Plan a Diet
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Page Content -->
    <main class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-light mb-3 custom-card">
                        <div class="card-body">
                            <h5 class="card-title">Plan Your Diet</h5>
                            <?php if ($success_message) { ?>
                                <div class="alert alert-success"><?php echo $success_message; ?></div>
                            <?php } ?>
                            <?php if ($error_message) { ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php } ?>
                            <form method="post" action="diet.php">
                                <div class="mb-3">
                                    <label for="diet_plan" class="form-label">Diet Plans</label>
                                    <textarea class="form-control" id="diet_plan" name="diet_plan[]" rows="5" required><?php echo htmlspecialchars(str_replace(array("\r\n", "\n"), "<br>", implode("\n", $diet_plans))); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Diet Plans</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery-3.5.1.js"></script>
</body>

</html>
