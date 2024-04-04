<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id']) && $_SESSION['login_status'] == "true") {
    header("Location: index.php");
    exit();
} else {
    $userid = $_SESSION['user_id'];
    $sql = $conn->prepare("SELECT * FROM userdetails WHERE id = ?");
    $sql->bind_param("i", $_SESSION['user_id']);
    $sql->execute();
    $result = $sql->get_result();

    $row = $result->fetch_assoc();
    $welcomeMessage = "Welcome To Dashboard " . $row['name'];
}
$currentDateTime = date('Y-m-d H:i:s');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboardstyle.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Link to your CSS file -->

    <style>
        .welcome {
            margin-top: 20px;
        }

        .upcoming-live-video {
            margin-top: 70px;
        }

        .upcoming-events-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .post-container {
            margin-top: 20px;
        }

        .post {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .post-details h5 {
            color: #333;
        }

        .comment-container {
            margin-top: 10px;
        }

        .comment {
            margin-bottom: 10px;
        }

        .comment-user {
            font-weight: bold;
            color: #333;
        }

        .comment-input {
            border-radius: 20px;
        }

        .submit-button {
            border-radius: 20px;
            margin-left: 10px;
        }

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
    <main class="container dascontainer mt-5">
        <div class="welcome text-center mt-4">
            <h2><?php echo $welcomeMessage; ?></h2>
        </div>

        <!-- Embedding YouTube Live Video -->
        <?php
        // Your existing PHP code for fetching and displaying live videos
        $currentDateTime = date('Y-m-d H:i:s');

        $live_sql = "SELECT * FROM live_video WHERE startson <= '$currentDateTime'";
        $live_result = $conn->query($live_sql);

        // Function to extract YouTube video ID from the link
        function extractIframeSrc($iframeTag)
        {
            // Use regex to extract the src attribute from the iframe tag
            preg_match('/src="([^"]+)"/', $iframeTag, $matches);

            // Check if src attribute was found
            if (isset($matches[1])) {
                return $matches[1]; // Return the src attribute value
            } else {
                return ''; // Return empty string if src attribute not found
            }
        }

        if ($live_result->num_rows > 0) {
            // Output data of each row
            while ($live_row = $live_result->fetch_assoc()) {
                $videoLink = $live_row["link"];
                $videoId = extractIframeSrc($videoLink);
                echo "<div class='post'>";
                echo "<h3 class='live-title'>Live</h3>";
                echo "<div class='live-video-container'>";
                echo "<div class='img-fluid'>";
                // Paste the whole YouTube iframe embed code directly from the database
                echo "<iframe width='100%' height='300px' src='{$videoId}' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' allowfullscreen></iframe>";
                echo "</div>";
                echo "</div>";
                echo "<div class='post-details'>";
                echo "<h5 class='mt-2'>{$live_row["title"]}</h5>";
                echo "<h5>Comments</h5>";
                echo "</div>";
                echo "<div class='comment-container'>";
                // Display comments or any other details if needed

                // Fetch and display live comments for this live video
                $liveId = $live_row['id'];
                $liveCommentsSql = "SELECT live_comment.comment, userdetails.name FROM live_comment INNER JOIN userdetails ON live_comment.userid = userdetails.id WHERE live_comment.liveid = ?";
                $stmt = $conn->prepare($liveCommentsSql);
                $stmt->bind_param("i", $liveId);
                $stmt->execute();
                $liveCommentsResult = $stmt->get_result();

                if ($liveCommentsResult->num_rows > 0) {
                    while ($liveCommentRow = $liveCommentsResult->fetch_assoc()) {
                        echo "<div class='comment'>";
                        echo "<p class='comment-user'>" . htmlspecialchars($liveCommentRow['name']) . ":</p>";
                        echo "<p class='comment-text'>" . htmlspecialchars($liveCommentRow['comment']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No comments yet.</p>";
                }
                echo "</div>"; // End of comment-container

                // Live comment form
                echo "<div class='comment-form'>";
                echo "<form action='add_live_comment.php' method='post' class='d-flex'>";
                echo "<div class='mb-2 flex-grow-1'>";

                echo "<input type='text' name='comment' class='form-control comment-input' placeholder='Add a comment...' required>";
                echo "<input type='hidden' name='live_id' value='{$live_row["id"]}'>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary submit-button'>Submit</button>";
                echo "<div class='mb-2'>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            // echo "<p>No live videos available.</p>";
        }

        ?>

        <!-- Upcoming Events -->

        <div class="upcoming-live-video position-fixed top-0 end-0 mt-5 me-5">
            <!-- <h3>Upcoming Live Video</h3> -->
            <div class="upcoming-events-container mt-5 me-2">
                <h3 class="text-center mb-3">Upcoming Events</h3>
                <div class="list-group">
                    <?php
                    // Fetch upcoming events from the live_video table
                    $upcoming_sql = "SELECT * FROM live_video WHERE startson > '$currentDateTime' ORDER BY startson ASC";
                    $upcoming_result = $conn->query($upcoming_sql);

                    if ($upcoming_result->num_rows > 0) {
                        while ($upcoming_row = $upcoming_result->fetch_assoc()) {
                            echo '<div class="card">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . htmlspecialchars($upcoming_row['title']) . '</h5>';
                            echo '<p class="card-text">Scheduled Time: ' . htmlspecialchars($upcoming_row['startson']) . '</p>';
                            // Display other details of the upcoming live video
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p class='text-muted'>No upcoming events.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>


        <div class="row mt-5">
            <div class="col-md-8 mx-auto">
                <div class="post-container">
                    <?php

                    $sql = "SELECT postdetails.id, postdetails.userid, postdetails.fileaddress, postdetails.title, postdetails.description, postdetails.time, userdetails.name FROM postdetails INNER JOIN userdetails ON postdetails.userid = userdetails.id ORDER BY postdetails.time DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='post'>";
                            // Check if the file address is an image or video
                            if (strpos($row["fileaddress"], '.jpg') !== false || strpos($row["fileaddress"], '.png') !== false || strpos($row["fileaddress"], '.jpeg') !== false || strpos($row["fileaddress"], '.gif') !== false) {
                                echo "<img src='assets/uploads/{$row["userid"]}/{$row["fileaddress"]}' alt='Post Image' class='img-fluid'>";
                            } elseif (strpos($row["fileaddress"], '.mp4') !== false || strpos($row["fileaddress"], '.avi') !== false || strpos($row["fileaddress"], '.mov') !== false || strpos($row["fileaddress"], '.wmv') !== false) {
                                echo "<video src='assets/uploads/{$row["userid"]}/{$row["fileaddress"]}' controls class='img-fluid'></video>";
                            } else {
                                echo "Unsupported media format.";
                            }
                            echo "<div class='post-details'>";
                            echo "<h5>{$row["title"]}</h5>";
                            echo "<p class='mb-1'>{$row["description"]}</p>";
                            echo "<p class='mb-2'><small class='text-muted'>Posted by {$row["name"]} on {$row["time"]}</small></p>";
                            echo "<h5>Comments</h5>";
                            echo "</div>"; // End of post-details

                            // Comment section
                            echo "<div class='comment-container'>";

                            // Fetch and display comments for this post
                            $postId = $row['id'];
                            $commentsSql = "SELECT comments.comment, userdetails.name FROM comments INNER JOIN userdetails ON comments.userid = userdetails.id WHERE comments.postid = $postId";
                            $commentsResult = $conn->query($commentsSql);

                            if ($commentsResult->num_rows > 0) {
                                while ($commentRow = $commentsResult->fetch_assoc()) {
                                    echo "<div class='comment'>";
                                    echo "<p class='comment-user'>" . htmlspecialchars($commentRow['name']) . ":</p>";
                                    echo "<p class='comment-text'>  " . htmlspecialchars($commentRow['comment']) . "</p>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No comments yet.</p>";
                            }
                            echo "</div>"; // End of comment-container

                            // Comment form
                            echo "<div class='comment-form'>";
                            echo "<form action='add_comment.php' method='post' class='d-flex'>";
                            echo "<div class='mb-2 flex-grow-1'>";
                            
                            echo "<input type='text' class='form-control comment-input' id='comment' name='comment' placeholder='Add a comment...' required>";
                            echo "<input type='hidden' name='post_id' value='$postId'>";
                            echo "</div>";
                            echo "<button type='submit' class='btn btn-primary submit-button'>Submit</button>";
                            echo "<div class='mb-2'>";
                            echo "</form>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>"; // End of post
                        }
                    } else {
                        echo "<p>No posts found.</p>";
                    }
                    ?>
                </div> <!-- End of post-container -->
            </div>
        </div>

    </main>
    <footer class="text-center mt-5">
        <!-- <div class="container">
            <form action="uploadpost.php" method="post">
                <button type="submit" class="btn btn-primary">Upload Post</button>
            </form>
            <form action="livepost.php" method="post">
                <button type="submit" class="btn btn-primary">Post live event</button>
            </form>
            <p>&copy; 2023</p>
        </div> -->
    </footer>
    </div>
    <!-- End of container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
</body>

</html>