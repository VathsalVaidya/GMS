<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gym";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT COUNT(*) AS pending_members FROM members WHERE status = 'reg pending'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$pending_members = $row['pending_members'];

mysqli_close($conn);

echo $pending_members;
?>
