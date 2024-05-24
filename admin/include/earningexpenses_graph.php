<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gym";

$connection = mysqli_connect($servername, $username, $password, $database);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch total earnings from members
$sql_earnings = "SELECT SUM(amount) AS total_earnings FROM members";
$result_earnings = mysqli_query($connection, $sql_earnings);
$row_earnings = mysqli_fetch_assoc($result_earnings);
$total_earnings = $row_earnings['total_earnings'];

// Fetch total expenses from equipment
$sql_expenses = "SELECT SUM(amount) AS total_expenses FROM equipments";
$result_expenses = mysqli_query($connection, $sql_expenses);
$row_expenses = mysqli_fetch_assoc($result_expenses);
$total_expenses = $row_expenses['total_expenses'];
?>

<div class="card bg-light mb-3 custom-card">
    <div class="card-body">
        <h5 class="card-title"><i class="fas fa-chart-bar"></i> Earnings and Expenses</h5>
        <canvas id="earningsExpensesChart"></canvas>
    </div>
</div>

<script>
    $(document).ready(function () {
        var earningsExpensesData = {
            labels: ['Total Earnings', 'Total Expenses'],
            datasets: [{
                label: 'Amount',
                backgroundColor: ['rgba(54, 162, 235, 0.8)', 'rgba(255, 99, 132, 0.8)'],
                data: [<?php echo $total_earnings; ?>, <?php echo $total_expenses; ?>]
            }]
        };

        var ctx = document.getElementById('earningsExpensesChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: earningsExpensesData,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Earnings and Expenses',
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
                            stepSize: 1000 // Adjust step size as needed
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
    });
</script>