<?php
include('db.php');
session_start();

if (isset($_POST['live_id']) && isset($_POST['comment'])) {
    // Sanitize inputs
    $live_id = mysqli_real_escape_string($conn, $_POST['live_id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Prepare and execute SQL statement to insert live comment
    $sql = $conn->prepare("INSERT INTO live_comment (userid, liveid, comment) VALUES (?, ?, ?)");
    $sql->bind_param("iis", $user_id, $live_id, $comment);
    if ($sql->execute()) {
        // Comment added successfully
        header("Location: dashboard.php"); // Redirect to dashboard or wherever appropriate
        exit();
    } else {
        // Error occurred while adding comment
        echo "Error: " . $sql->error;
    }
} else {
    // Redirect user if they accessed this page without proper parameters
    header("Location: dashboard.php");
    exit();
}
