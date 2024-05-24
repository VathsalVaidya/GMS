<?php
include '../config.php';

// Check if user_id is provided and is numeric
if(isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    // Escape user_id to prevent SQL injection
    $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
    
    // Construct the DELETE query
    $sql = "DELETE FROM members WHERE user_id = $user_id";
    
    // Execute the DELETE query
    if(mysqli_query($connection, $sql)) {
        // Redirect to view_members.php after successful deletion
        header("Location: view_members.php");
        exit();
    } else {
        // Error handling if deletion fails
        echo "Error: " . mysqli_error($connection);
    }
} else {
    // Redirect to view_members.php if user_id is not provided or is not numeric
    header("Location: view_members.php");
    exit();
}

// Close the connection
mysqli_close($connection);
?>
