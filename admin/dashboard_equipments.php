<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gym";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT COUNT(*) AS available_equipments FROM equipments";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$available_equipments = $row['available_equipments'];

mysqli_close($conn);

echo $available_equipments;
?>
