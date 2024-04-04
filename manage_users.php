<?php
include('db.php');
session_start();

// Check if user is logged in and has admin privileges (role = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php"); // Redirect unauthorized users
    exit();
}

// Fetch users from the database
$sql = "SELECT * FROM userdetails";
$result = $conn->query($sql);

// Handle delete user request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    $deleteSql = "DELETE FROM userdetails WHERE id = $userId";
    if ($conn->query($deleteSql) === TRUE) {
        // User deleted successfully
        header("Location: manage_users.php");
        exit();
    } else {
        // Failed to delete user
        echo "Error: Unable to delete user.";
    }
}

// Handle block/unblock user request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['block_user'])) {
    $userId = $_POST['user_id'];
    $blocked =  $_POST['blocked'];
    if ($blocked == 0) {
        $blocked = 1;
    } else {
        $blocked = 0;
    }
    $blockSql = "UPDATE userdetails SET blocked = $blocked WHERE id = $userId";
    if ($conn->query($blockSql) === TRUE) {
        // User status updated successfully
        header("Location: manage_users.php");
        exit();
    } else {
        // Failed to update user status
        echo "Error: Unable to update user status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Bootstrap CSS -->
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
            max-width: 1100px;
            width: 100%;
        }

        th,
        td {
            text-align: center;
            vertical-align: middle;
        }

        .btn-group {
            display: flex;
            justify-content: center;
        }

        .btn-primary,
        .btn-primary:hover,
        .btn-primary:active,
        .btn-primary:focus {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-danger,
        .btn-danger:hover,
        .btn-danger:active,
        .btn-danger:focus {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-warning,
        .btn-warning:hover,
        .btn-warning:active,
        .btn-warning:focus {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-info,
        .btn-info:hover,
        .btn-info:active,
        .btn-info:focus {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .card-body {
            background-color: #d1cdcd;
        }

        h2 {
            text-align: center;
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

    <div class="container" style="padding-left: 70px; padding-top:20px;">
        <div class="card">
            <div class="card-body">
                <h2 class="mt-2 mb-4">Manage Users</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Country</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['contact'] . "</td>";
                                    echo "<td>" . $row['country'] . "</td>";
                                    echo "<td>" . ($row['role'] == 1 ? 'Admin' : 'User') . "</td>";
                                    echo "<td class='btn-group'>";
                                    echo "<form action='manage_users.php' method='post'>";
                                    echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                                    echo "<input type='hidden' name='blocked' value='" . $row['blocked'] . "'>";

                                    echo "<button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#editModal" . $row['id'] . "'>Edit</button>";
                                    echo "<button type='submit' class='btn btn-danger ms-1' name='delete_user'>Delete</button>";
                                    echo "<button type='submit' class='btn btn-danger ms-1' name='block_user' value='" . ($row['blocked'] == 1 ? 0 : 1) . "'>" . ($row['blocked'] == 1 ? 'Unblock' : 'Block') . "</button>";
                                    echo "</form>";
                                    echo "</td>";
                                    // Edit User Modal
                                    echo "<div class='modal fade' id='editModal" . $row['id'] . "' tabindex='-1' aria-labelledby='editModalLabel" . $row['id'] . "' aria-hidden='true'>";
                                    echo "<div class='modal-dialog'>";
                                    echo "<div class='modal-content'>";
                                    echo "<div class='modal-header'>";
                                    echo "<h5 class='modal-title' id='editModalLabel" . $row['id'] . "'>Edit User</h5>";
                                    echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                                    echo "</div>";
                                    echo "<div class='modal-body'>";
                                    echo "<form action='edit_user.php' method='post'>";
                                    echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                                    echo "<div class='mb-3'>";
                                    echo "<label for='name' class='form-label'>Name</label>";
                                    echo "<input type='text' class='form-control' id='name' name='name' value='" . $row['name'] . "' required>";
                                    echo "</div>";
                                    echo "<div class='mb-3'>";
                                    echo "<label for='email' class='form-label'>Email</label>";
                                    echo "<input type='email' class='form-control' id='email' name='email' value='" . $row['email'] . "' required>";
                                    echo "</div>";
                                    echo "<div class='mb-3'>";
                                    echo "<label for='contact' class='form-label'>Contact</label>";
                                    echo "<input type='text' class='form-control' id='contact' name='contact' value='" . $row['contact'] . "' required>";
                                    echo "</div>";
                                    echo "<div class='mb-3'>";
                                    echo "<label for='country' class='form-label'>Country</label>";
                                    echo "<input type='text' class='form-control' id='country' name='country' value='" . $row['country'] . "' required>";
                                    echo "</div>";
                                    echo "<div class='mb-3'>";
                                    echo "<label for='role' class='form-label'>Role</label>";
                                    echo "<select class='form-select' id='role' name='role' required>";
                                    echo "<option value='1'" . ($row['role'] == 1 ? ' selected' : '') . ">Admin</option>";
                                    echo "<option value='2'" . ($row['role'] == 2 ? ' selected' : '') . ">User</option>";
                                    echo "</select>";
                                    echo "</div>";
                                    echo "<button type='submit' class='btn btn-primary'>Save changes</button>";
                                    echo "</form>";

                                    // Reset Password Button
                                    echo "<form action='mail_reset_password.php' method='post'>";
                                    echo "<input type='hidden' name='email' value='" . $row['email'] . "'>";
                                    echo "<button type='submit' class='btn btn-info mt-2' name='reset_password' value='" . $row['email'] . "'>Reset Password</button>";
                                    echo "</form>";

                                    echo "</div>";
                                    echo "<div class='modal-footer'>";
                                    echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";

                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No users found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>