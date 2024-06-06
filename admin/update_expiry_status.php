<?php
include '../config.php';

// Get today's date
$today = date("Y-m-d");

// Query to fetch all members whose expiry date is less than today and not already expired
$query = "SELECT fullname, service FROM members WHERE expiry_date < ? AND status != 'expired'";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $fullname = $row['fullname'];
    $service = $row['service'];

    // Update status to 'expired' for these members
    $update_status_query = "UPDATE members SET status='expired' WHERE fullname=? AND service=?";
    $update_stmt = $connection->prepare($update_status_query);
    $update_stmt->bind_param("ss", $fullname, $service);
    $update_stmt->execute();
    $update_stmt->close();
}

// Close the statement and connection
$stmt->close();
$connection->close();

echo "Expired statuses updated successfully.";
?>
