<?php
include('db.php');
session_start();
$userid = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['post_id'])) {
        $postId = $_POST['post_id'];

        // Fetch post details
        $sql = $conn->prepare("SELECT * FROM postdetails WHERE id = ?");
        $sql->bind_param("i", $postId);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $postTitle = $row['title'];
            // Fetch comments based on user role
            if ($_SESSION['role'] == 1) {
                $commentsSql = $conn->prepare("SELECT comments.*, userdetails.name AS username FROM comments INNER JOIN userdetails ON comments.userid = userdetails.id WHERE postid = ?");
                $commentsSql->bind_param("i", $postId);
            } else {
                $commentsSql = $conn->prepare("SELECT comments.*, userdetails.name AS username FROM comments INNER JOIN userdetails ON comments.userid = userdetails.id WHERE postid = ? AND comments.userid = ?");
                $commentsSql->bind_param("ii", $postId, $userid);
            }
            $commentsSql->execute();
            $commentsResult = $commentsSql->get_result();
        } else {
            // Handle case where post does not exist
            echo ("Post not found.");
            exit();
        }
    } else {
        // Handle case where post_id is not set
        echo ("Post ID not set.");
        exit();
    }
} else {
    // Handle case where request method is not POST
    echo ("Invalid request method.");
    exit();
}

// Delete comment if delete button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_comment'])) {
    $commentId = $_POST['comment_id'];

    // Delete comment from database
    $deleteSql = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $deleteSql->bind_param("i", $commentId);

    if ($deleteSql->execute()) {
        // Comment deleted successfully
        header("Location: manage_comments.php");
        exit();
    } else {
        // Failed to delete comment
        echo "Error: Unable to delete comment.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
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

    <div class="container" style="padding-top: 40px; padding-left:80px;">
        <h2 class="mt-5 mb-4">Manage Comments for <?php echo $postTitle; ?></h2>
        <div class="row">
            <?php
            if ($commentsResult->num_rows > 0) {
                while ($commentRow = $commentsResult->fetch_assoc()) {
                    // Display each comment
                    $commentId = $commentRow['id'];
                    $commentText = $commentRow['comment'];
                    $commentUser = $commentRow['username']; // Fetched username
            ?>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="card-text"><strong><?php echo $commentUser; ?>:</strong> <?php echo $commentText; ?></p>
                                <!-- Add form to delete each comment -->
                                <form action="delete_comment.php" method="post">
                                    <input type="hidden" name="comment_id" value="<?php echo $commentId; ?>">
                                    <button type="submit" class="btn btn-danger" name="delete_comment">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-muted'>No comments found for this post.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>