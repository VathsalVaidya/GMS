<?php
    include '../config.php';
    include 'include/check_session.php';

    // Retrieve billing data from the database
    $query = mysqli_query($connection, "SELECT fullname, service, plan, amount, expiry_date, status FROM members");

    // Close the database connection
    mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Billing Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Your custom DataTables CSS -->
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <!-- Sidebar CSS -->
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
                top: 56px;
                /* Adjust top position to below navbar */
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
            /* Background color */
            border: none;
            /* Remove border */
        }

        .dropdown-menu a {
            color: #fff;
            /* Text color */
        }

        .dropdown-menu a:hover {
            background-color: #495057;
            /* Hover background color */
        }

        .dropdown-divider {
            border-top: 1px solid #6c757d;
            /* Divider color */
        }

        .empty-message {
            font-size: 18px;
            color: #666;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
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
                        <a href="dashboard.php" class="nav-link px-3">
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
                        <li class="nav-item">
            <a class="nav-link" href="../members/member-registration.php" target="_blank">
                <i class="bi bi-person-plus"></i>
                Register New Member
            </a>
        </li>
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
                    <?php
                    // Check if there are any records
                    if (mysqli_num_rows($query) > 0) {
                        // Display the table header
                        echo "<h2 class='text-center'>Entered Billing Data</h2>";
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-striped table-bordered'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr><th>Fullname</th><th>Service</th><th>Plan</th><th>Amount</th><th>Expiry Date</th><th>Status</th><th>Action</th><th>Alert</th></tr>";
                        echo "</thead><tbody>";

                        // Loop through the records and display each row
                        while ($row = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<td>" . $row['fullname'] . "</td>";
                            echo "<td>" . $row['service'] . "</td>";
                            echo "<td>" . $row['plan'] . "</td>";
                            echo "<td>" . $row['amount'] . "</td>";
                            echo "<td>" . $row['expiry_date'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td>
                                <button class='btn btn-success btn-sm update-btn' data-username='" . $row['fullname'] . "' data-service='" . $row['service'] . "' data-plan='" . $row['plan'] . "' data-amount='" . $row['amount'] . "' data-expiry-date='" . $row['expiry_date'] . "'>
                                    <i class='bi bi-pencil'></i> Update Payment
                                </button>
                            </td>";
                            echo "<td>
                                <button class='btn btn-warning btn-sm alert-btn' data-username='" . $row['fullname'] . "' data-service='" . $row['service'] . "' data-plan='" . $row['plan'] . "' data-amount='" . $row['amount'] . "' data-expiry-date='" . $row['expiry_date'] . "'>
                                    <i class='bi bi-exclamation-triangle'></i> Alert
                                </button>
                            </td>";
                            echo "</tr>";
                        }

                        // Close the table
                        echo "</tbody></table></div>";
                    } else {
                        // If no records found, display a message
                        echo "<div class='text-center'>";
                        echo "<p class='empty-message'>No billing data found. Try adding some billing data to see results!</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap5.min.js"></script>
    <script src="js/script.js"></script>
<script>
    $(document).ready(function () {
        // Manually initialize the offcanvas component
        var offcanvasElement = document.getElementById('sidebar');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);

        $('.update-btn').click(function () {
            // Get the data attributes of the clicked button
            var fullname = $(this).data('username');
            var service = $(this).data('service');
            var period = $(this).data('period');
            var amount = $(this).data('amount');
            var expiryDate = $(this).data('expiry-date');

            // Redirect to the updatePayment.php page with data parameters
            window.location.href = 'updatePayment.php?username=' + fullname + '&service=' + service + '&period=' + period + '&amount=' + amount + '&expiry_date=' + expiryDate;
        });

        $('.alert-btn').click(function () {
            // Get the data attributes of the clicked button
            var fullname = $(this).data('username');
            var service = $(this).data('service');
            var period = $(this).data('period');
            var amount = $(this).data('amount');
            var expiryDate = $(this).data('expiry-date');

            // Redirect to the alert.php page with data parameters
            window.location.href = 'alert.php?username=' + fullname + '&service=' + service + '&period=' + period + '&amount=' + amount + '&expiry_date=' + expiryDate;
        });
    });
</script>


</body>
</html>
