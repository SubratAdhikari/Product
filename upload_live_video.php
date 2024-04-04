<?php

session_start();
$userid = $_SESSION['user_id'];
$link = $_POST['link'];
$title = htmlspecialchars($_POST['title']);
$startOn = htmlspecialchars($_POST['startTime']);


if (isset($_POST['submit']) && isset($_POST['link']) && isset($_POST['startTime'])) {
    include "db.php";

    // Use prepared statements to prevent SQL injection
    $sql = $conn->prepare("INSERT INTO live_video (userid, link, title, startson) 
               VALUES (?,?,?,?)");

    $sql->bind_param("isss", $userid, $link, $title, $startOn);
    if ($sql->execute()) {
        echo "<script>
        alert('Live video details uploaded successfully.');
        window.location.href='dashboard.php';
        </script>";
    } else {
        $em = "Error uploading live video details.";
        echo "<script>
        alert('$em');
        window.location.href='dashboard.php';
        </script>";
    }
} else {
    header("Location: dashboard.php");
}
