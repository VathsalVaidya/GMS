<?php
include '../config.php';

// Check if user_id is provided and is numeric
if(isset($_GET['equipment_id']) && is_numeric($_GET['equipment_id'])) {
    // Escape user_id to prevent SQL injection
    $equipment_id = mysqli_real_escape_string($connection, $_GET['equipment_id']);
    
    // Construct the DELETE query
    $sql = "DELETE FROM equipments WHERE equipment_id = $equipment_id";
    
    // Execute the DELETE query
    if(mysqli_query($connection, $sql)) {
        // Redirect to view_equipments.php after successful deletion
        header("Location: view_equipments.php");
        exit();
    } else {
        // Error handling if deletion fails
        echo "Error: " . mysqli_error($connection);
    }
} else {
    // Redirect to view_equipments.php if equipment_id is not provided or is not numeric
    header("Location: view_equipments.php");
    exit();
}

// Close the connection
mysqli_close($connection);
?>
