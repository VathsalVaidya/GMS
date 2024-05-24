    <?php
    include '../config.php';
    include 'include/check_session.php';

    // Fetch equipments data from the database
    $sql = "SELECT * FROM equipments";
    $result = mysqli_query($connection, $sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Equipments</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

            .table-container {
                margin-top: 50px;
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Equipments Data</h5>
                            </div>
                            <div class="card-body p-0">
                                <!-- Display success message if exists -->
                                <?php if(isset($_SESSION['message'])): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $_SESSION['message']; ?>
                                </div>
                                <?php unset($_SESSION['message']); endif; ?>

                                <!-- Display error message if exists -->
                                <?php if(isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $_SESSION['error']; ?>
                                </div>
                                <?php unset($_SESSION['error']); endif; ?>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Equipment Id</th>
                                                <th>Name</th>
                                                <th>Amount</th>
                                                <th>Quantity</th>
                                                <th>Vendor</th>
                                                <th>Description</th>
                                                <th>Address</th>
                                                <th>Contact</th>
                                                <th>Date Added</th>
                                                <th>Update</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row["equipment_id"] . "</td>";
                                                    echo "<td>" . $row["name"] . "</td>";
                                                    echo "<td>" . $row["amount"] . "</td>";
                                                    echo "<td>" . $row["quantity"] . "</td>";
                                                    echo "<td>" . $row["vendor"] . "</td>";
                                                    echo "<td>" . $row["description"] . "</td>";
                                                    echo "<td>" . $row["address"] . "</td>";
                                                    echo "<td>" . $row["contact"] . "</td>";
                                                    echo "<td>" . $row["date"] . "</td>";
                                                    echo "<td>
                                                            <a href='update_equipment.php?equipment_id=" . $row["equipment_id"] . "' class='btn btn-sm btn-primary'>Update</a>
                                                        </td>"; // Update button
                                                    echo "<td>
                                                            <button class='btn btn-sm btn-danger' onclick='confirmDelete(" . $row["equipment_id"] . ")'>Remove</button>
                                                        </td>"; // Remove button
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='11'>No equipments found</td></tr>";
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

        <!-- Modal for Confirmation -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to remove the equipment data?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a id="deleteButton" href="#" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            // Function to handle confirmation dialog
            function confirmDelete(equipmentId) {
                // Set the href of the delete button in the confirmation modal
                document.getElementById("deleteButton").href = "remove_equipment.php?equipment_id=" + equipmentId;
                // Show the confirmation modal
                $('#confirmationModal').modal('show');
            }
        </script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
        <script src="./js/jquery-3.5.1.js"></script>
        <script src="./js/jquery.dataTables.min.js"></script>
        <script src="./js/dataTables.bootstrap5.min.js"></script>
        <script src="./js/script.js"></script>
    </body>

    </html>
