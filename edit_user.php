<?php
include('db.php');
session_start();

// Check if user is logged in and has admin privileges (role = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php"); // Redirect unauthorized users
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $country = $_POST['country'];
    $role = $_POST['role'];

    // Update user details in the database
    $updateSql = "UPDATE userdetails SET name = '$name', email = '$email', contact = '$contact', country = '$country', role = '$role' WHERE id = $userId";

    if ($conn->query($updateSql) === TRUE) {
        // User details updated successfully
        header("Location: manage_users.php");
        exit();
    } else {
        // Failed to update user details
        echo "Error: Unable to update user details.";
    }
} else {
    // Handle case where request method is not POST
    echo "Invalid request method.";
    exit();
}
