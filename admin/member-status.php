<?php
include '../config.php';
include 'include/check_session.php';

// Fetch members data from the database
$sql = "SELECT * FROM members";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Status</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
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

        .table-container {
            margin-top: 50px;
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
                        <a class="nav-link px-3" href="view_billing.php" role="button">
                            <i class="bi bi-cash me-2"></i>
                            Billing
                        </a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link px-3 active" data-bs-toggle="collapse" href="#membersCollapse" role="button"
                        aria-expanded="false" aria-controls="membersCollapse">
                        <i class="bi bi-people me-2"></i>
                        Members
                    </a>
                    <div class="collapse" id="membersCollapse">
                        <a class="nav-link px-3" href="view_members.php">View Members Data</a>
                        <a class="nav-link px-3 active" href="member-status.php">Check Members Status</a>
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
        <div class="container table-container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Member Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Contact Number</th>
                                            <th>Plan</th>
                                            <th>Chosen Service</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                echo "<td>" . $row["user_id"] . "</td>";
                                                echo "<td>" . $row["fullname"] . "</td>";
                                                echo "<td>" . $row["phone"] . "</td>";
                                                echo "<td>" . $row["plan"] . "</td>"; 
                                                echo "<td>" . $row["service"] . "</td>"; 
                                                
                                                // Determine payment status and display accordingly
                                                echo "<td>";
                                                $status = $row['status'];
                                                switch ($status) {
                                                    case "New":
                                                        echo "<span class='badge bg-warning'>Reg Pending</span>";
                                                        break;
                                                    case "active":
                                                        echo "<span class='badge bg-success'>Active</span>";
                                                        break;
                                                    case "expired":
                                                        echo "<span class='badge bg-danger'>Expired</span>";
                                                        break;
                                                    default:
                                                        echo "<span class='badge bg-secondary'>$status</span>";
                                                }
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No members found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
