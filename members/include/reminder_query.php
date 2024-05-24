<?php
include '../config.php';
include 'include/check_session.php';

$user_id = $_SESSION['username'];

$qry = "SELECT reminder FROM members WHERE username=?";
$stmt = $connection->prepare($qry);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$connection->close();
?>
