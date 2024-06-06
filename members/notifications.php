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

// Fetch fullname from database based on the logged-in user
$username = $_SESSION['username'];
$query = "SELECT fullname FROM members WHERE username=?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 1) {
    mysqli_stmt_bind_result($stmt, $fullname);
    mysqli_stmt_fetch($stmt);
} else {
    // Handle error if fullname is not found
    $fullname = "Fullname not found";
}
mysqli_stmt_close($stmt);

// Fetch announcements from the database
$announcements = [];
$query = "SELECT message, date FROM announcements";
$result = mysqli_query($connection, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $announcements[] = $row;
    }
    mysqli_free_result($result);
} else {
    // Handle error if unable to fetch announcements
    $error_message = "Error fetching announcements: " . mysqli_error($connection);
}

// Count reminders for the logged-in user
$qry_count = "SELECT COUNT(*) AS reminder_count FROM members WHERE username=? AND reminder=1";
$stmt_count = mysqli_prepare($connection, $qry_count);
mysqli_stmt_bind_param($stmt_count, "s", $username);
mysqli_stmt_execute($stmt_count);
$result_count = mysqli_stmt_get_result($stmt_count);
$row_count = mysqli_fetch_assoc($result_count);
$reminder_count = $row_count['reminder_count'];

mysqli_stmt_close($stmt_count);
mysqli_close($connection);
?>#
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
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

        .announcement-title {
            border-bottom: 1px solid #dee2e6; /* Add border below title */
            padding-bottom: 10px; /* Add some bottom padding for better spacing */
            margin-bottom: 20px; /* Add some bottom margin for better spacing */
        }

        .announcement-content {
            border-bottom: 1px solid #dee2e6; /* Add border between notifications */
            padding-bottom: 10px; /* Add some bottom padding for better spacing */
            margin-bottom: 10px; /* Add some bottom margin for better spacing */
            position: relative; /* Set position to relative */
        }

        .announcement-date {
            position: absolute; /* Set position to absolute */
            bottom: 0; /* Align to the bottom */
            right: 0; /* Align to the right */
            font-size: 0.8rem; /* Adjust font size */
            color: #6c757d; /* Adjust color */
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
                    <a href="notifications.php" class="nav-link px-3 active" >
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
                            <h5 class="card-title announcement-title">Notifications</h5>
                            <?php
                            if (!empty($announcements)) {
                                foreach ($announcements as $announcement) {
                                    echo "<div class='announcement-content'>";
                                    echo "<p class='mb-0'>" . htmlspecialchars($announcement['message']) . "</p>";
                                    echo "<p class='mb-0 announcement-date'>" . htmlspecialchars($announcement['date']) . "</p>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No notifications available.</p>";
                            }
                            ?>
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
