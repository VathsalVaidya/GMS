<?php
include '../config.php';
include 'include/check_session.php';

$user_id = $_SESSION['username'];

// Count reminders and display notification count
$qry_count = "SELECT COUNT(*) AS reminder_count FROM members WHERE username=? AND reminder=1";
$stmt_count = $connection->prepare($qry_count);
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$reminder_count = $row_count['reminder_count'];

$stmt_count->close();
$connection->close();
?>
