<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
</body>
</html>