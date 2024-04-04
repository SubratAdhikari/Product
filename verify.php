<?php
// include('db.php');
// session_start();

// if (isset($_SESSION['user_id']) && isset($_SESSION['login_status'])) {
//     if ($_SESSION['login_status'] == "true") {
//         header("Location: dashboard.php");
//         exit();
//     }
// } else {
//     header("Location: index.php");
//     exit();
// }
?>


<?php
include('db.php');
session_start();
if (isset($_GET['email']) && isset($_GET['v_code'])) {

    $query = "SELECT * FROM `userdetails` WHERE `email`='$_GET[email]' AND `varification_code`='$_GET[v_code]'";

    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $result_fetch = mysqli_fetch_assoc($result);
            if ($result_fetch['is_varified'] == 0) {
                $update = "UPDATE `userdetails` SET `is_varified`='1' WHERE `email`='$result_fetch[email]'";
                if (mysqli_query($conn, $update)) {
                    echo "
                    <script>
                    alert('Email verification sucessfull');
                    window.location.href='index.php';
                    </script>
                    ";
                } else {
                    echo "
                    <script>
                    alert('Email already verified');
                    window.location.href='index.php';
                    </script>
                    ";
                }
            } else {
                echo "
                <script>
                alert('Email already varified');
                window.location.href='index.php';
                </script>
                ";
            }
        }
    } else {
        echo "
    <script>
    alert('Cannot run query');
    window.location.href='index.php';
    </script>
    ";
    }
}
