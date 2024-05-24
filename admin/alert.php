<?php
// Check if the required data is provided
if (isset($_GET['username']) && isset($_GET['service']) && isset($_GET['expiry_date'])) {
    $username = $_GET['username'];
    $service = $_GET['service'];
    $expiry_date = $_GET['expiry_date'];

    include '../config.php';

    // Construct the query to send a reminder
    $qry = "UPDATE members SET reminder = '1' WHERE fullname = ? AND service = ? AND expiry_date = ?";
    $stmt = $connection->prepare($qry);
    $stmt->bind_param("sss", $username, $service, $expiry_date);

    if ($stmt->execute()) {
        echo '<script>alert("Reminder notification sent to ' . $username . ' for service ' . $service . '!");</script>';
        echo '<script>window.location.href = "view_billing.php";</script>';
    } else {
        echo '<script>alert("Error: Unable to send reminder notification!");</script>';
        echo '<script>window.location.href = "view_billing.php";</script>';
    }

    $stmt->close();
    $connection->close();
} else {
    echo '<script>alert("Error: Missing required data!");</script>';
    echo '<script>window.location.href = "view_billing.php";</script>';
}
?>
