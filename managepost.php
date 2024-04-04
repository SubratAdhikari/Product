<?php
include('db.php');
session_start();
$userid = $_SESSION['user_id'];
$userRole = $_SESSION['role']; // Assuming 'role' is stored in the session

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="dashboardstyle.css" />

    <style>
        .card-body {
            background-color: #d1cdcd;
        }
    </style>

</head>

<body>

    <header>
        <nav class="navbar navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">SubSport</a>
                <form action="logout.php" method="post">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </nav>
    </header>
    <div class="side-panel">
        <a href="dashboard.php" class="active">Home</a>
        <a href="uploadpost.php">Upload Post</a>
        <a href="managepost.php">Manage Post</a>

        <?php
        if ($_SESSION['role'] == 1) {

            echo "<a href='livepost.php'>Post live events</a>";
            echo "<a href='manage_users.php'>Manage User</a>";
        }
        ?>


        <!-- Add more buttons as needed -->
    </div>

    <div class="container" style="padding-top:30px; padding-left:50px;">
        <?php
        // Prepare the SQL query based on user role
        if ($userRole == 1) {
            // Admin user, fetch all posts
            $sql = "SELECT live_video.id, live_video.title, live_video.time, live_video.startson, live_video.userid, userdetails.name FROM live_video INNER JOIN userdetails ON live_video.userid = userdetails.id ORDER BY live_video.time DESC";
            $result = $conn->query($sql);

            echo "<h2 class='mt-5 mb-4'>Live Video</h2>";
            echo "<div class='row'>";

            // Execute the SQL query and display posts

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $postId = $row['id'];
                    $postTitle = $row['title'];
                    $postDateTime = $row['time'];
                    $startson = $row['startson'];
                    $postedBy = ($_SESSION["user_id"] != $row['userid']) ? $row['name'] : "You"; // Display user's name or "You"
        ?>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $postTitle; ?></h5>
                                <p class="card-text">live on: <?php echo $startson; ?>
                                    <br> Uploaded by: <?php echo $postedBy; ?> at <?php echo $postDateTime; ?>
                                </p>
                                <div class="d-flex justify-content-end">
                                    <!-- Form to manage comments -->
                                    <form action="manage_live_comment.php" method="post">
                                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                                        <button type="submit" class="btn btn-info me-2">View Comments</button>
                                    </form>

                                    <!-- Form to delete post -->
                                    <form action="delete_live_post.php" method="post">
                                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

        <?php

                }
            } else {
                echo "<p class='text-muted'>No posts found.</p>";
            }

            echo "</div>";
        }
        ?>

        <h2 class="mt-5 mb-4">Manage Posts</h2>
        <div class="row">
            <?php
            // Prepare the SQL query based on user role
            if ($userRole == 1) {
                // Admin user, fetch all posts
                $sql = "SELECT postdetails.id, postdetails.title, postdetails.time, userdetails.name, postdetails.userid
                        FROM postdetails 
                        INNER JOIN userdetails ON postdetails.userid = userdetails.id 
                        ORDER BY postdetails.time DESC";
                $result = $conn->query($sql);
            } else {
                // Regular user, fetch only their posts
                $sql = $conn->prepare("SELECT postdetails.id, postdetails.title, postdetails.time, userdetails.name, postdetails.userid
                        FROM postdetails 
                        INNER JOIN userdetails ON postdetails.userid = userdetails.id 
                        WHERE postdetails.userid = ? ORDER BY postdetails.time DESC");
                $sql->bind_param("i", $userid);
                $sql->execute();
                $result = $sql->get_result();
            }

            // Execute the SQL query and display posts

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $postId = $row['id'];
                    $postTitle = $row['title'];
                    $postDateTime = $row['time'];
                    $postedBy = ($_SESSION["user_id"] != $row['userid']) ? $row['name'] : "You"; // Display user's name or "You"

                    // Display the post card
            ?>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $postTitle; ?></h5>
                                <p class="card-text"> Uploaded by: <?php echo $postedBy; ?> at <?php echo $postDateTime; ?> </p>

                                <div class="d-flex justify-content-end">
                                    <!-- Form to manage comments -->
                                    <form action="manage_comments.php" method="post">
                                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                                        <button type="submit" class="btn btn-info me-2">View Comments</button>
                                    </form>

                                    <!-- Form to delete post -->
                                    <form action="deletepost.php" method="post">
                                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-muted'>No posts found.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>