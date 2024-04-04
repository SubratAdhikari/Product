<?php
session_start(); // Start the session

if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to a login page after logout
    header("Location: index.php");
    exit();
}
