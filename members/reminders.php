<?php
include 'include/check_session.php';

// Now you can access $_SESSION['user_id'] safely
$user_id = $_SESSION['username'];

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
$qry = "SELECT reminder FROM members WHERE username=?";
$stmt = $connection->prepare($qry);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();

// Count reminders and display notification count
$qry_count = "SELECT COUNT(*) AS reminder_count FROM members WHERE username=? AND reminder=1";
$stmt_count = $connection->prepare($qry_count);
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$reminder_count = $row_count['reminder_count'];

$stmt_count->close();
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminders - Gym System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Custom styles for this template */
        body {
            padding-top: 56px;
        }

        @media (min-width: 768px) {
            body {
                padding-top: 60px;
            }

            .sidebar-nav {
                height: 100vh;
                position: fixed;
                top: 56px; /* Adjust top position to below navbar */
                left: 0;
                width: 250px;
                overflow-y: auto;
            }

            .main-content {
                margin-left: 250px;
                padding-top: 1rem; /* Add padding to prevent overlap */
            }

            .navbar-nav {
                margin-left: auto;
            }
        }

        .sidebar-nav {
            background-color: #343a40;
            color: #fff;
        }

        .sidebar-nav .nav-link {
            color: #fff;
        }

        .sidebar-nav .nav-link:hover {
            background-color: #495057;
        }

        .nav-item.active .nav-link {
            background-color: #212529;
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
                    <a href="reminders.php" class="nav-link px-3 active">
                        <i class="bi bi-calendar-check me-2"></i>
                        Reminders
                        <?php if ($reminder_count > 0) { ?>
                            <span class="badge bg-danger"><?php echo $reminder_count; ?></span>
                        <?php } ?>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 p-3">
                <?php if ($row['reminder'] == '1') { ?>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">ALERT</h4>
                        <p>This is to notify you that your current membership program is going to expire soon. Please clear up your payments before your due dates. <br>IT IS IMPORTANT THAT YOU CLEAR YOUR DUES ON TIME IN ORDER TO AVOID SERVICE DISRUPTIONS.</p>
                        <hr>
                        <p class="mb-0">We value you as our customer and look forward to continue serving you in the future.</p>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">NO REMINDERS YET!</h4>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="./js/bootstrap.bundle.min.js"></script>
<script src="./js/jquery-3.5.1.js"></script>
</body>
</html>

