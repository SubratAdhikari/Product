<?php
include('db.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="dashboardstyle.css" />
    <style>
        #mediaPreview {
            display: none;
            margin: auto;
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

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Add Post</h5>
                <form action="upload.php" method="post" enctype="multipart/form-data">

                    <div class="mb-3" id="mediaPreview" style="width: 600px; height: 350px;"></div>

                    <div class="mb-3">
                        <label for="mediaInput" class="form-label">Select Image or Video:</label>
                        <input type="file" class="form-control" id="mediaInput" name="media" accept="image/*, video/*" onchange="displayMedia(event)">
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <script src="https://unpkg.com/bootstrap-icons@1.10.0/font/bootstrap-icons.js"></script>

    <script>
        function displayMedia(event) {
            const input = event.target;
            const previewContainer = document.getElementById('mediaPreview');
            const file = input.files[0];
            const fileType = file.type;

            if (fileType.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function() {
                    const imgElement = document.createElement('img');
                    imgElement.src = reader.result;
                    imgElement.classList.add('img-thumbnail');
                    imgElement.style.width = '100%';
                    imgElement.style.height = '100%';
                    previewContainer.innerHTML = '';
                    previewContainer.style.display = 'block'; // Show preview container
                    previewContainer.appendChild(imgElement);
                }
                reader.readAsDataURL(file);
            } else if (fileType.startsWith('video/')) {
                const videoElement = document.createElement('video');
                videoElement.src = URL.createObjectURL(file);
                videoElement.controls = true;
                videoElement.classList.add('img-thumbnail');
                videoElement.style.width = '100%';
                videoElement.style.height = '100%';
                previewContainer.innerHTML = '';
                previewContainer.style.display = 'block'; // Show preview container
                previewContainer.appendChild(videoElement);
            } else {
                alert('Unsupported file format');
            }
        }
    </script>

</body>

</html>