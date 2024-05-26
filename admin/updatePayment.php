<?php
include '../config.php';
include 'include/check_session.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = $_POST['fullname'];
    $service = $_POST['service'];
    $plan = $_POST['plan'];
    $amount = $_POST['amount'];
    $expiry_date = $_POST['expiry_date'];

    // Update billing data in the database
    $update_query = "UPDATE members SET plan=?, amount=?, expiry_date=? WHERE fullname=? AND service=?";
    $stmt = $connection->prepare($update_query);
    $stmt->bind_param("sssss", $plan, $amount, $expiry_date, $fullname, $service);
    $stmt->execute();
    $stmt->close();

    // Check if expiry date has passed
    $today = date("Y-m-d");
    if ($expiry_date < $today) {
        // Update status to 'expired' in the database
        $update_status_query = "UPDATE members SET status='expired' WHERE fullname=? AND service=? AND status != 'expired'";
        $stmt = $connection->prepare($update_status_query);
        $stmt->bind_param("ss", $fullname, $service);
        $stmt->execute();
        $stmt->close();
    } else {
        // Update status based on new expiry date
        if (!empty($amount)) {
            // Update status to 'active' if amount is paid and expiry date is in the future
            $update_status_query = "UPDATE members SET status='active' WHERE fullname=? AND service=?";
            $stmt = $connection->prepare($update_status_query);
            $stmt->bind_param("ss", $fullname, $service);
            $stmt->execute();
            $stmt->close();
        } else {
            // If amount is not paid, keep status as 'expired'
            // This avoids changing the status to 'active' if payment is not made
        }
    }

    // Set reminder value to zero
    $update_reminder_query = "UPDATE members SET reminder=0 WHERE fullname=? AND service=?";
    $stmt = $connection->prepare($update_reminder_query);
    $stmt->bind_param("ss", $fullname, $service);
    $stmt->execute();
    $stmt->close();

    echo "Billing data updated successfully.";

    // Redirect back to view_billing.php after update
    header("Location: view_billing.php");
    exit();
}

// If not submitted via POST, fetch billing data from database
if (isset($_GET['username']) && isset($_GET['service'])) {
    $username = $_GET['username'];
    $service = $_GET['service'];

    // Retrieve billing data for the selected user and service
    $query = "SELECT * FROM members WHERE fullname=? AND service=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $username, $service);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $fullname = $row['fullname'];
        $service = $row['service'];
        $plan = $row['plan'];
        $amount = $row['amount'];
        $expiry_date = $row['expiry_date'];
    } else {
        echo "Billing data not found for the selected user and service.";
        exit();
    }
} else {
    echo "User or service not specified.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Payment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
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
                        <a href="index.html" class="nav-link px-3">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 active" href="view_billing.php" role="button">
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-12 p-3">
                    <!-- Your existing content goes here -->
                    <div class="container mt-5">
                        <h2 class="mb-4">Update Payment</h2>
                        <form method="post">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    value="<?php echo $fullname; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="service" class="form-label">Service</label>
                                <input type="text" class="form-control" id="service" name="service"
                                    value="<?php echo $service; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="plan" class="form-label">Plan</label>
                                <select class="form-select" id="plan" name="plan">
                                    <option value="basic" <?php if ($plan == 'basic') echo 'selected'; ?>>Basic</option>
                                    <option value="standard" <?php if ($plan == 'standard') echo 'selected'; ?>>Standard</option>
                                    <option value="premium" <?php if ($plan == 'premium') echo 'selected'; ?>>Premium</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount"
                                    value="<?php echo $amount; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date"
                                    value="<?php echo $expiry_date; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
