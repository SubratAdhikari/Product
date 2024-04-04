<?php

session_start();
$userid = $_SESSION['user_id'];
$description = htmlspecialchars($_POST['description']);
$title = htmlspecialchars($_POST['title']);

if (isset($_POST['submit']) && isset($_FILES['media'])) {
    include "db.php";

    echo "<pre>";
    print_r($_FILES['media']);
    echo "</pre>";

    $media_name = $_FILES['media']['name'];
    $media_size = $_FILES['media']['size'];
    $tmp_name = $_FILES['media']['tmp_name'];
    $error = $_FILES['media']['error'];

    if ($error === 0) {
        if ($media_size < 1) {
            $em = "Sorry, your file is too large.";
            echo ($em);
            // header("Location: dashboard.php?error=$em");
        } else {
            $media_ex = pathinfo($media_name, PATHINFO_EXTENSION);
            $media_ex_lc = strtolower($media_ex);

            $allowed_exs = array("jpg", "jpeg", "png", "mp4");

            if (in_array($media_ex_lc, $allowed_exs)) {

                $upath = 'assets/uploads/' . $userid;
                if (!file_exists($upath)) {
                    // Create the directory
                    if (mkdir($upath, 0777, true)) {
                        echo "Directory created successfully at: " . $upath;
                    } else {
                        echo "Error creating directory at: " . $upath;
                    }
                } else {
                    echo "Directory already exists at: " . $upath;
                }
                $new_media_name = uniqid("Media-", true) . '.' . $media_ex_lc;
                $media_upload_path = $upath . '/' . $new_media_name;
                move_uploaded_file($tmp_name, $media_upload_path);

                // Use prepared statements to prevent SQL injection
                $sql = $conn->prepare("INSERT INTO postdetails (userid, fileaddress, title, description) 
                           VALUES (?,?,?,?)");

                $sql->bind_param("isss", $userid, $new_media_name, $title, $description);
                $sql->execute();

                echo "<script>
                alert('uploaded sucessfully .');
                window.location.href='dashboard.php';
                </script>";
            } else {
                $em = "You can't upload files of this type";
                echo ($em);
                // header("Location: dashboard.php?error=$em");
            }
        }
    } else {
        $em = "unknown error occurred!";
        echo ($em);
        // header("Location: dashboard.php?error=$em");
    }
} else {
    header("Location: dashboard.php");
}
