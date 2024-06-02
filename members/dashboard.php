<?php
session_start();
include '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: member-login.php");
    exit();
}

// Fetch fullname and expiry date from database based on the logged-in user
$username = $_SESSION['username'];
$query = "SELECT fullname, expiry_date FROM members WHERE username=?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 1) {
    mysqli_stmt_bind_result($stmt, $fullname, $expiry_date);
    mysqli_stmt_fetch($stmt);
} else {
    // Handle error if fullname is not found
    $fullname = "Fullname not found";
    $expiry_date = null;
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
$stmt_count = $connection->prepare($qry_count);
$stmt_count->bind_param("s", $username);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$reminder_count = $row_count['reminder_count'];

$stmt_count->close();
mysqli_close($connection);

// Calculate the number of days until the expiry date
$days_until_expiry = null;
if ($expiry_date) {
    $expiry_date_time = new DateTime($expiry_date);
    $current_date_time = new DateTime();
    $interval = $current_date_time->diff($expiry_date_time);
    $days_until_expiry = $interval->days;

    // Check if the expiry date is within the next 7 days
    $expiry_alert = $expiry_date_time >= $current_date_time && $days_until_expiry <= 7;
} else {
    $expiry_alert = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Dashboard</title>
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

        .notification-card {
            padding: 5px; /* Adjust padding as needed */
            max-width: 450px; /* Adjust maximum width as needed */
            margin: left; /* Center the card horizontally */
        }

        .notification-window {
            max-height: 150px; /* Adjust height as needed */
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            background-color: #f8f9fa;
            margin-top: 20px;
        }

        .announcement-content {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 10px;
            position: relative;
        }

        .announcement-date {
            position: absolute;
            bottom: 0;
            right: 0;
            font-size: 0.8rem;
            color: #6c757d;
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
                        <a href="dashboard.php" class="nav-link px-3 active">
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
                            <h5 class="card-title"> Welcome</h5>
                            <p class="card-text"><?php echo htmlspecialchars($fullname); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card bg-light mb-3 custom-card notification-card">
                        <div class="card-body">
                            <h5 class="card-title">Notifications</h5>
                            <div class="notification-window">
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
                <?php if ($expiry_alert) { ?>
                <div class="col-lg-12">
                    <div class="alert alert-warning" role="alert">
                        Your membership will expire in <?php echo $days_until_expiry; ?> days. Please pay your bill to renew your membership.
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery-3.5.1.js"></script>
</body>

</html>
