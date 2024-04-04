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
    <title>Post Live Video</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="dashboardstyle.css" />
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            max-width: 600px;
            width: 100%;
        }

        .card-body {
            background-color: #d1cdcd;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-dark bg-dark fixed-top">
            <div class="container-fluid"> <!-- Modified container class to container-fluid -->
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
    </div>

    <div class="container"> <!-- Moved container inside body content -->
        <div class="card">
            <div class="card-body">
                <h1 class="text-center mb-4">Post Live Video</h1>
                <form action="upload_live_video.php" method="post">
                    <div class="form-group mb-2">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="link">YouTube Video Link:</label>
                        <input type="text" id="link" name="link" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="startTime">Schedule Start Time:</label>
                        <input type="datetime-local" id="startTime" name="startTime" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Post Live Video</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>