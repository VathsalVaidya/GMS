<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gym";

$connection = mysqli_connect($servername, $username, $password, $database);

// Check if the connection is successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT gender, COUNT(*) AS gender_count FROM members GROUP BY gender";
$result = mysqli_query($connection, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

$genders = [];
$gender_counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $genders[] = $row['gender'];
    $gender_counts[] = $row['gender_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gender Distribution</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        #genderChart {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <canvas id="genderChart" width="400" height="400"></canvas>

    <script>
        var ctxGender = document.getElementById('genderChart').getContext('2d');
        var genderChart = new Chart(ctxGender, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($genders); ?>,
                datasets: [{
                    label: 'Gender Distribution',
                    data: <?php echo json_encode($gender_counts); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)', // Red
                        'rgba(54, 162, 235, 0.8)', // Blue
                        'rgba(255, 206, 86, 0.8)', // Yellow
                        'rgba(75, 192, 192, 0.8)', // Teal
                        'rgba(153, 102, 255, 0.8)', // Purple
                        'rgba(255, 159, 64, 0.8)' // Orange
                    ],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Gender Distribution',
                        font: {
                            size: 24,
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
                }
            }
        });
    </script>
</body>
</html>
