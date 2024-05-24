<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gym";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT SUM(amount) AS total_expenses FROM equipments";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$total_expenses = $row['total_expenses'];

mysqli_close($conn);

echo $total_expenses;
?>
