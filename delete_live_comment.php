<?php
// Include your database connection file
include('db.php');

// Check if the form has been submitted and the comment ID is provided
if (isset($_POST['delete_comment']) && isset($_POST['comment_id'])) {
    // Sanitize the input to prevent SQL injection
    $commentId = mysqli_real_escape_string($conn, $_POST['comment_id']);

    // SQL query to delete the comment
    $sql = "DELETE FROM live_comment WHERE id = $commentId";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Comment deleted successfully
        header("Location: dashboard.php"); // Redirect back to the manage comments page
        exit();
    } else {
        // Error occurred while deleting the comment
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If the form was not submitted properly, redirect to the manage comments page
    header("Location: dashboard.php");
    exit();
}
