<?php
include 'C:/xampp/htdocs/Gym/config.php';

// Set the default timezone (adjust as needed)
date_default_timezone_set('Asia/Kathmandu'); 

// Get the current date
$today = date("Y-m-d");
echo "Today's date: " . $today . "\n"; // Debugging

// Fetch and display expiry dates for debugging
$query = "SELECT fullname, expiry_date FROM members WHERE expiry_date < ? AND status = 'active'";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo "Expiring member: " . $row['fullname'] . ", Expiry Date: " . $row['expiry_date'] . "\n"; // Debugging
}
$stmt->close();

// Update status to 'expired' for all members whose expiry date has passed and status is 'active'
$update_status_query = "UPDATE members SET status='expired' WHERE expiry_date < ? AND status = 'active'";
$stmt = $connection->prepare($update_status_query);
$stmt->bind_param("s", $today);

if ($stmt->execute()) {
    echo "Status updated successfully.\n";
} else {
    echo "Error updating status: " . $stmt->error . "\n"; // Debugging
}

$stmt->close();
?>
