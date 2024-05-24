<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gym";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT SUM(amount) AS total_earnings FROM members";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$total_earnings = $row['total_earnings'];

mysqli_close($conn);

echo $total_earnings;
?>
