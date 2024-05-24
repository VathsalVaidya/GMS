<?php
include '../config.php';
include 'include/check_session.php';

// Check if user_id is provided
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch member data based on user_id
    $sql = "SELECT * FROM members WHERE user_id = '$user_id'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $fullname = $row['fullname'];
        $username = $row['username'];
        $dob = $row['dob'];
        $phone = $row['phone'];
        $address = $row['address'];
        $gender = $row['gender'];
        $plan = $row['plan'];
        $amount = $row['amount'];
        $service = $row['service'];
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "User ID not provided.";
    exit();
}

// Update member data
if (isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $plan = $_POST['plan'];
    $amount = $_POST['amount'];
    $service = $_POST['service'];

    // Prepare SQL statement to update data in the database
    $update_sql = "UPDATE members SET fullname = '$fullname', dob = '$dob', phone = '$phone', address = '$address', gender = '$gender', plan = '$plan', amount = '$amount', service = '$service' WHERE user_id = '$user_id'";

    // Execute the SQL query
    $update_query = mysqli_query($connection, $update_sql);

    // Check if the query was successful
    if ($update_query) {
        // Redirect to view_members.php with a success message
        header("Location: view_members.php?success=1");
        exit();
    } else {
        echo "Failed to update member data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Member</title>
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

        .form-card {
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-card-header {
            background-color: #007bff;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }

        .form-card-body {
            padding: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-submit {
            width: 100%;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand ms-2 text-uppercase fw-bold" href="#">GYM STATION</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['admin_username']; ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card form-card">
                        <div class="card-header form-card-header">
                            <h5 class="card-title mb-0 text-center">Update Member</h5>
                        </div>
                        <div class="card-body form-card-body">
                            <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
                                <div class="alert alert-success" role="alert">
                                    Member data updated successfully.
                                </div>
                            <?php endif; ?>
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname"
                                        value="<?php echo $fullname; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dob" name="dob"
                                        value="<?php echo $dob; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="<?php echo $phone; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address"
                                        rows="3"><?php echo $address; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                                        <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                                        <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="plan" class="form-label">Plan</label>
                                    <select class="form-select" name="plan" id="plan" required>
                                        <option value="" disabled selected>Choose Plan</option>
                                        <option value="basic" <?php if ($plan == 'basic') echo 'selected'; ?>>Basic</option>
                                        <option value="standard" <?php if ($plan == 'standard') echo 'selected'; ?>>Standard</option>
                                        <option value="premium" <?php if ($plan == 'premium') echo 'selected'; ?>>Premium</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="service" class="form-label">Service</label>
                                    <select class="form-select" name="service" id="service" required>
                                        <option value="" disabled selected>Choose Service</option>
                                        <option value="personal-trainer" <?php if ($service == 'personal-trainer') echo 'selected'; ?>>Personal Trainer</option>
                                        <option value="group-classes" <?php if ($service == 'group-classes') echo 'selected'; ?>>Group Classes</option>
                                        <option value="nutrition-counseling" <?php if ($service == 'nutrition-counseling') echo 'selected'; ?>>Nutrition Counseling</option>
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-submit" name="update">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>

