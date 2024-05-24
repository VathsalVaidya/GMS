<?php
session_start();
// Check if the admin is not logged in, redirect to login page
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin-login.php");
    exit();
}
include 'include/check_session.php';

$username = $_SESSION['admin_username'];
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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
            .dropdown-menu {
        background-color: #343a40; /* Background color */
        border: none; /* Remove border */
    }

    .dropdown-menu a {
        color: #fff; /* Text color */
    }

    .dropdown-menu a:hover {
        background-color: #495057; /* Hover background color */
    }

    .dropdown-divider {
        border-top: 1px solid #6c757d; /* Divider color */
    }
    .custom-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .custom-card:hover {
        transform: translateY(-5px);
    }
    .card-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    }
    .card-title{
    font-size: 20px;
    font-weight: bold;
    font-family: Georgia, 'Times New Roman', Times, serif;
    }
    .card-text{
    font-size: 15px;
    font-family: 'Courier New', Courier, monospace;
    font-weight: 600;
    }
.card1{
    margin-top: 30px;
}
.card2{
    margin-top: 30px;
}
.card3{
    margin-top: 30px;
}

    .card-body1{
        background: rgb(152, 225, 152);
        border-radius: 10px;
        padding: 1.2 rem;
        width: 290px;
        height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.5s ease-in-out;
    }
    .card-body2{
        background: rgb(163, 161, 232);
        border-radius: 10px;
        padding: 1.2 rem;
        width: 290px;
        height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.5s ease-in-out;
    }
    .card-body3{
        background: rgb(255, 162, 162);
        border-radius: 10px;
        padding: 1.2 rem;
        width: 290px;
        height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.5s ease-in-out;
    }
    .icon1{
        color: #fff;
        padding: 0.5rem;
        height: 50px;
        width: 50px;
        text-align: center;
        border-radius: 50%;
        font-size: 1.5rem;
        background: green;
    }
    .icon2{
        color: #fff;
        padding: 0.5rem;
        height: 50px;
        width: 50px;
        text-align: center;
        border-radius: 50%;
        font-size: 1.5rem;
        background: blueviolet;
    }
    .icon3{
        color: #fff;
        padding: 0.5rem;
        height: 50px;
        width: 50px;
        text-align: center;
        border-radius: 50%;
        font-size: 1.5rem;
        background: crimson;
    }
    .btn1{
        color: #fff;;
        border-radius: 40px;
        background-color: green;
        font-family: sans-serif;
        font-weight: bolder;
    }
    .btn2{
        color: #fff;;
        border-radius: 40px;
        background-color: blueviolet;
        font-family: sans-serif;
        font-weight: bolder;
    }
    .btn3{
        color: #fff;;
        border-radius: 40px;
        background-color: crimson;
        font-family: sans-serif;
        font-weight: bolder;
    }
    .card-title1, .card-title2, .card-title3,.card-title4, .card-title5, .card-title6{
        font-size: 15px;
    font-weight: bold;
    font-family: Georgia, 'Times New Roman', Times, serif;
    }
    .card-text1, .card-text2, .card-text3,.card-text4, .card-text5, .card-text6{
        font-size: 20px;
    font-family: 'Courier New', Courier, monospace;
    font-weight: 600;
    }

/* Improved Announcement Styling */

/* Container styling */
.announcement-container {
    max-height: 300px; /* Limit height to allow scrolling if necessary */
    overflow-y: auto; /* Enable vertical scrolling */
}

/* Individual announcement styling */
.announcement {
    padding: 15px; /* Add padding around each announcement */
    border-bottom: 1px solid #e5e5e5; /* Light border between announcements */
    transition: background-color 0.3s ease; /* Smooth transition on hover */
}

.announcement:hover {
    background-color: #f5f5f5; /* Light background color on hover */
}

/* Date styling */
.announcement-date {
    font-size: 12px;
    color: #999; /* Lighter text color */
    margin-bottom: 5px; /* Add space between date and message */
}

/* Message styling */
.announcement-message {
    font-size: 16px;
    color: #333; /* Dark text color */
    line-height: 1.5; /* Improved line spacing for readability */
}

/* Add subtle box shadow to give depth */
.announcement {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Animation on hover */
.announcement:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}


        </style>
    </head>
    <body>

    <?php include 'include/navbar.php';?>
    <?php include 'include/sidebar.php';?>

    <!-- Page Content -->
    <main class="main-content">
        <div class="container-fluid">
            <!-- Content goes here -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="card1 bg-light mb-3 custom-card ">   
                        <div class="card-wrapper">
                            <div class="card-body card-body1">
                                <i class="bi bi-cash me-2 icon1"></i>
                            <h5 class="card-title ">Billing</h5>
                            <p class="card-text">Manage billing information.</p>
                            <a href="view_billing.php" class="btn btn1">Go to Billing</a>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card2 bg-light mb-3 custom-card">
                        <div class ="card-wrapper">
                        <div class="card-body card-body2">
                            <i class="bi bi-people me-2 icon2"></i>

                            <h5 class="card-title ">Members</h5>
                            <p class="card-text">Manage member data.</p>
                            <a href="view_members.php" class="btn btn2">Go to Members</a>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card3 bg-light mb-3 custom-card">
                        <div class="card-wrapper">
                            <div class="card-body card-body3">
                            <i class="fas fa-dumbbell icon3"></i>
                                <h5 class="card-title">Equipments</h5>
                                <p class="card-text">Manage equipment data.</p>
                                <a href="view_equipments.php" class="btn btn3">Go to Equipments</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="row mb-4">
    <div class="col-lg-6">
        <div class="card bg-light mb-3 custom-card">
            <div class="card-body card-body4">
                <h5 class="card-title"><i class="fas fa-chart-pie"></i> Services Report</h5>
                <canvas id="serviceChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row">
            <!-- Total Gym Members Card -->
            <div class="col-lg-4">
                <div class="card bg-light mb-3 custom-card">
                    <div class="card-body card-body5">
                        <h5 class="card-title1"><i class="fas fa-users"></i> Total Gym Members</h5>
                        <p class="card-text1">
                         <?php include 'dashboard_totalmembers.php'; ?> 
                        </p>
                    </div>
                </div>
            </div>

            <!-- Available Equipments Card -->
            <div class="col-lg-4">
                <div class="card bg-light mb-3 custom-card">
                    <div class="card-body card-body6">
                        <h5 class="card-title2"><i class="fas fa-dumbbell"></i> Available Equipments</h5>
                        <p class="card-text2">
                            <?php include 'dashboard_equipments.php'; ?> 
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Expenses on Equipments Card -->
            <div class="col-lg-4">
                <div class="card bg-light mb-3 custom-card">
                    <div class="card-body card-body7">
                        <h5 class="card-title3"><i class="fas fa-dollar-sign"></i> Total Expenses</h5>
                        <p class="card-text3">
                           Rs.<?php include 'dashboard_expenses.php'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Earnings from Members Card -->
            <div class="col-lg-4">
                <div class="card bg-light mb-3 custom-card">
                    <div class="card-body card-body8">
                        <h5 class="card-title4"><i class="fas fa-money-bill-wave"></i> Total Earnings</h5>
                        <p class="card-text4">
                            Rs.<?php include 'dashboard_earnings.php'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total active Gym Members -->
            <div class="col-lg-4">
                <div class="card bg-light mb-3 custom-card">
                    <div class="card-body card-body9">
                        <h5 class="card-title5"><i class="fas fa-user-check"></i> Active Members</h5>
                        <p class="card-text5">
                            <?php include 'dashboard_activemembers.php'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Registration on Pending Members -->
            <div class="col-lg-4">
                <div class="card bg-light mb-3 custom-card">
                    <div class="card-body card-body10">
                        <h5 class="card-title6"><i class="fas fa-user-clock"></i> Pending Registration</h5>
                        <p class="card-text6">
                            <?php include 'dashboard_regpending.php'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

<!-- Announcement -->
<div class="col">
    <a href="announcement.php" style="text-decoration: none; color: inherit;">
        <div class="card bg-light mb-3 custom-card">
            <div class="card-body card-body11">
                <h5 class="card-title">Announcement <i class="fas fa-bullhorn"></i></h5>
                <p class="card-text">
                    Make an Announcement
                </p>
            </div>
        </div>
    </a>
</div>
</div>
</div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card bg-light mb-3 custom-card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chart-pie"></i>Registered Gym Members by Gender: Overview</h5>
             <?php include "include/gender_piechart.php";?>
            </div>
        </div>
    </div>
<!-- Add a new container for announcements -->
<div class="col-lg-6">
    <div class="card bg-light mb-3 custom-card">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-bullhorn"></i> Announcements</h5>
            <?php
            // Fetch announcements from the database
            $announcements_query = "SELECT message, date FROM announcements ORDER BY date DESC";
            $announcements_result = mysqli_query($connection, $announcements_query);

            // Check if there are any announcements
            if (mysqli_num_rows($announcements_result) > 0) {
                while ($announcement_row = mysqli_fetch_assoc($announcements_result)) {
                    $message = $announcement_row['message'];
                    $date = $announcement_row['date'];
                    // Display each announcement
            ?>
                    <div class='announcement'>
                        <p class='announcement-date'><?php echo $date; ?></p>
                        <p class='announcement-message'><?php echo $message; ?></p>
                    </div>
            <?php
                }
            } else {
                echo "<p>No announcements available.</p>";
            }
            ?>
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
  


    <!-- PHP to fetch data and create chart -->
    <?php
    include '../config.php';

    // Check if the connection is successful
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT service, COUNT(*) AS service_count FROM members GROUP BY service";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    $services = [];
    $counts = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = $row['service'];
        $counts[] = $row['service_count'];
    }

    // Rest of your code...
    ?>


<script>
    var ctx = document.getElementById('serviceChart').getContext('2d');
    ctx.canvas.height = 169; // Adjust the height as needed
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($services); ?>,
            datasets: [{
                label: 'Service Count',
                data: <?php echo json_encode($counts); ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Services Report',
                    font: {
                        size: 18,
                        weight: 'bold'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: "rgba(0, 0, 0, 0.1)"
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>


    </body>
    </html>
