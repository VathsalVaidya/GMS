<?php 
include '../config.php';

// Start a session
session_start();
include 'include/check_session.php';


if (isset($_POST["submit"])) {
    // Check if the connection is established
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data and escape special characters
    $name = mysqli_real_escape_string($connection, $_POST["name"]);
    $amount = mysqli_real_escape_string($connection, $_POST["amount"]);
    $quantity = mysqli_real_escape_string($connection, $_POST["quantity"]);
    $vendor = mysqli_real_escape_string($connection, $_POST["vendor"]);
    $description = mysqli_real_escape_string($connection, $_POST["description"]);
    $address = mysqli_real_escape_string($connection, $_POST["address"]);
    $contact = mysqli_real_escape_string($connection, $_POST["contact"]);
    $date = date("Y-m-d"); // Current date

    // Prepare SQL statement to insert data into the database
    $ins = "INSERT INTO equipments (name, amount, quantity, vendor, description, address, contact, date) VALUES ('$name','$amount','$quantity', '$vendor', '$description', '$address','$contact','$date')";

    // Execute the SQL query
    $query = mysqli_query($connection, $ins);

    // Check if the query was successful
    if ($query) {
        // Display success message
        $_SESSION['message'] = "Equipment details added successfully.";
        // Redirect to another page to prevent form resubmission
        header("Location: equipments.php");
        exit();
    } else {
        // Display an error message and the SQL error, if any
        $_SESSION['error'] = "Failed to insert data: " . mysqli_error($connection);
    }

    // Redirect to the equipment page to avoid form resubmission
    header("Location: equipments.php");
    exit();
}

// Unset session variables after displaying messages
unset($_SESSION['error']);
unset($_SESSION['message']);
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
                        <a class="nav-link px-3 active" href="equipments.php">Add New Equipments</a>
                        <a class="nav-link px-3" href="view_equipments.php">View Equipments Data</a>
                    </div>
                </li>
                      <li class="nav-item">
            <a class="nav-link" href="../members/member-registration.php" target="_blank">
                <i class="bi bi-person-plus"></i>
                Register New Member
            </a>
        </li>
            </ul>
        </nav>
    </div>
</div>

    <div class="wrapper">
        <h1>Add New Equipment</h1>
        <form method="POST">
            <?php if (isset($_SESSION['error'])) { ?>
                <p class="error"><?php echo $_SESSION['error']; ?></p>
            <?php } ?>
            <?php if (isset($_SESSION['message'])) { ?>
                <p class="message"><?php echo $_SESSION['message']; ?></p>
            <?php } ?>
            <div class="input-box">
                <input type="text" name="name" placeholder="Name" class="form-control" required>
            </div>
            <div class="input-box">
                <input type="text" name="amount" placeholder="Amount" class="form-control" required>
            </div>
            <div class="input-box">
                <input type="text" name="quantity" placeholder="Quantity" class="form-control" required>
            </div>
            <div class="input-box">
                <input type="text" name="vendor" placeholder="Vendor" class="form-control" required>
            </div>
            <div class="input-box">
                <textarea name="description" placeholder="Description" class="form-control" required></textarea>
            </div>
            <div class="input-box">
                <input type="text" name="address" placeholder="Address" class="form-control" required>
            </div>
            <div class="input-box">
                <input type="text" name="contact" placeholder="Contact" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add Equipment</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
