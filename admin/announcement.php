<?php

    session_start();


include '../config.php'; // Include database connection

include 'include/check_session.php';
// Define variables to store form input and error messages
$message = $date = $successMessage = $errorMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    if (empty($_POST["message"])) {
        $errorMessage = "Message is required";
    } else {
        $message = $_POST["message"];
    }

    if (empty($_POST["date"])) {
        $errorMessage = "Date is required";
    } else {
        $date = $_POST["date"];
    }

    // If no validation errors, proceed with database insertion
    if (empty($errorMessage)) {
        // Prepare and bind SQL statement
        $stmt = $connection->prepare("INSERT INTO announcements (message, date) VALUES (?, ?)");
        $stmt->bind_param("ss", $message, $date);

        // Execute the statement
        if ($stmt->execute()) {
            $successMessage = "Announcement added successfully.";
            // Clear form inputs after successful submission
            $message = $date = "";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection (if not using persistent connection)
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* Custom styles for this template */
        body {
            padding-top: 56px;
            padding-bottom: 50px;
        }

        @media (min-width: 768px) {
            body {
                padding-top: 60px;
            }

            .sidebar-nav {
                height: calc(100vh - 56px);
                /* Adjusted height to consider navbar */
                position: fixed;
                top: 56px;
                left: 0;
                width: 250px;
                overflow-y: auto;
            }

            .main-content {
                margin-left: 250px;
                padding-top: 1rem;
                /* Add padding to prevent overlap */
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

        .dropdown-menu {
            background-color: #343a40;
            border: none;
        }

        .dropdown-menu a {
            color: #fff;
        }

        .dropdown-menu a:hover {
            background-color: #495057;
        }

        .dropdown-divider {
            border-top: 1px solid #6c757d;
        }

        .empty-message {
            font-size: 18px;
            color: #666;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        /* Custom styles for the form */
        .container {
            margin-top: 70px; /* Adjusted margin to prevent overlap */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .wrapper h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .input-box {
            margin-bottom: 20px;
        }

        .input-box input, .input-box textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .input-box textarea {
            resize: none;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .error {
            color: #dc3545;
            margin-bottom: 10px;
            text-align: center;
        }

        .message {
            color: #28a745;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand ms-2 text-uppercase fw-bold" href="#">GYM STATION</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $_SESSION['admin_username']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="offcanvas offcanvas-start sidebar-nav bg-dark" tabindex="-1" id="sidebar">
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
                        <a class="nav-link px-3" href="view_billing.php" role="button">
                            <i class="bi bi-cash me-2"></i>
                            Billing
                        </a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link px-3" data-bs-toggle="collapse" href="#membersCollapse" role="button"
                        aria-expanded="false" aria-controls="membersCollapse">
                        <i class="bi bi-people me-2"></i>
                        Members
                    </a>
                    <div class="collapse" id="membersCollapse">
                        <a class="nav-link px-3" href="view_members.php">View Members Data</a>
                        <a class="nav-link px-3" href="member-status.php">Check Members Status</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" data-bs-toggle="collapse" href="#equipmentsCollapse" role="button"
                        aria-expanded="false" aria-controls="equipmentsCollapse">
                        <i class="fas fa-dumbbell"></i>
                        Equipments
                    </a>
                    <div class="collapse" id="equipmentsCollapse">
                        <a class="nav-link px-3" href="equipments.php">Add New Equipments</a>
                        <a class="nav-link px-3" href="view_equipments.php">View Equipments Data</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>

<div class="container mt-5">
    <div class="wrapper">
        <h1>Make an Announcement</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php if (!empty($errorMessage)) : ?>
                <p class="error"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
            <?php if (!empty($successMessage)) : ?>
                <p class="message"><?php echo $successMessage; ?></p>
            <?php endif; ?>
            <div class="input-box">
                <label for="message" class="form-label">Announcement Message:</label>
                <textarea id="message" name="message" rows="4" class="form-control"><?php echo htmlspecialchars($message); ?></textarea>
            </div>
            <div class="input-box">
                <label for="date" class="form-label">Date:</label>
                <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date); ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
