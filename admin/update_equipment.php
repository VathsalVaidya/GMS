<?php
include '../config.php';
include 'include/check_session.php';

// Check if equipment_id parameter is set in the URL
if (isset($_GET['equipment_id'])) {
    $equipment_id = $_GET['equipment_id'];

    // Fetch equipment data from the database based on equipment_id
    $sql = "SELECT * FROM equipments WHERE equipment_id = '$equipment_id'";
    $result = mysqli_query($connection, $sql);

    // Check if equipment exists
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Extract equipment data
        $name = $row['name'];
        $amount = $row['amount'];
        $quantity = $row['quantity'];
        $vendor = $row['vendor'];
        $description = $row['description'];
        $address = $row['address'];
        $contact = $row['contact'];

        // Check if form is submitted for updating equipment
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $equipment_id = $_POST['equipment_id'];
            $name = $_POST['name'];
            $amount = $_POST['amount'];
            $quantity = $_POST['quantity'];
            $vendor = $_POST['vendor'];
            $description = $_POST['description'];
            $address = $_POST['address'];
            $contact = $_POST['contact'];

            // Update equipment in the database
   // Prepare the SQL statement
$update_sql = "UPDATE equipments SET name = ?, amount = ?, quantity = ?, vendor = ?, description = ?, address = ?, contact = ? WHERE equipment_id = ?";
$stmt = mysqli_prepare($connection, $update_sql);

// Bind parameters
mysqli_stmt_bind_param($stmt, "siissssi", $name, $amount, $quantity, $vendor, $description, $address, $contact, $equipment_id);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    // Redirect to view_equipments.php after successful update
    $_SESSION['success'] = "Equipment updated successfully!";
    header("Location: view_equipments.php");
    exit();
} else {
    $_SESSION['error'] = "Error updating equipment: " . mysqli_error($connection);
}

        }
    } else {
        // Redirect to view_equipments.php if equipment does not exist
        $_SESSION['error'] = "Equipment not found!";
        header("Location: view_equipments.php");
        exit();
    }
} else {
    // Redirect to view_equipments.php if equipment_id parameter is not set
    $_SESSION['error'] = "Equipment ID not provided!";
    header("Location: view_equipments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Equipment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
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
                        <a class="nav-link px-3 active" data-bs-toggle="collapse" href="#equipmentsCollapse" role="button"
                            aria-expanded="false" aria-controls="equipmentsCollapse">
                            <i class="fas fa-dumbbell"></i>
                            Equipments
                        </a>
                        <div class="collapse" id="equipmentsCollapse">
                            <a class="nav-link px-3" href="equipments.php">Add New Equipments</a>
                            <a class="nav-link px-3 active" href="view_equipments.php">View Equipments Data</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Update Equipment</h5>
                        </div>
                        <div class="card-body">
                            <!-- Display error message if exists -->
                            <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['error']; ?>
                            </div>
                            <?php unset($_SESSION['error']); endif; ?>

                            <!-- Update Equipment Form -->
                            <form action="" method="POST">
                                <input type="hidden" name="equipment_id" value="<?php echo $equipment_id; ?>">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo $name; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        value="<?php echo $amount; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                        value="<?php echo $quantity; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="vendor" class="form-label">Vendor</label>
                                    <input type="text" class="form-control" id="vendor" name="vendor"
                                        value="<?php echo $vendor; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description"
                                        rows="3"><?php echo $description; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="<?php echo $address; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" class="form-control" id="contact" name="contact"
                                        value="<?php echo $contact; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Equipment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="./js/jquery-3.5.1.js"></script>
    <script src="./js/jquery.dataTables.min.js"></script>
    <script src="./js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/script.js"></script>
</body>

</html>
