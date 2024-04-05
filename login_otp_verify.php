
<?php
include('db.php');
include('mail_detail.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp'];
    if (isset($_SESSION['user_email']) && isset($otp)) {

        $query = "SELECT * FROM `userdetails` WHERE `email`='$_SESSION[user_email]' AND `login_otp`=$otp";
        $query2 = "SELECT * FROM `userdetails` WHERE `email`='$_SESSION[user_email]'";
        $result = mysqli_query($conn, $query);
        $result2 = mysqli_query($conn, $query2);
        $result_fetch = mysqli_fetch_assoc($result);
        $result_fetch2 = mysqli_fetch_assoc($result2);
        if ($result) {
            if (mysqli_num_rows($result) == 1 && $result_fetch['login_otp_attemp'] <= 2) {
                $result_fetch = mysqli_fetch_assoc($result);

                $userId = $_SESSION['user_id']; // Assuming you have the user ID stored in session
                $sql = "SELECT blocked FROM userdetails WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $sresult = $stmt->get_result();

                if ($sresult->num_rows > 0) {
                    $row = $sresult->fetch_assoc();
                    $blocked = $row['blocked'];

                    if (
                        $blocked == 1
                    ) {
                        // Unset all session variables
                        session_unset();

                        // Destroy the session
                        session_destroy();
                        echo "<script>alert('Your account is blocked. Please contact the administrator.');
                        window.location.href='index.php';
                        </script>";
                        exit();
                    } else {
                        $_SESSION['login_status'] = "true";
                        header("Location: dashboard.php");
                        exit();
                    }
                }
            } else {
                $attemp = $result_fetch2['login_otp_attemp'] + 1;
                if ($attemp == 4) {
                    $v_code = bin2hex(random_bytes(16));
                    $updatevarification = "UPDATE `userdetails` SET `is_varified`='0',`login_otp_attemp`= 0, `varification_code`='$v_code' WHERE `email`='$_SESSION[user_email]'";
                    mysqli_query($conn, $updatevarification);

                    sendmail($_SESSION['user_email'], $v_code);
                    echo "
                <script>
                alert('Too many attempts verify email again');
                window.location.href='index.php';
                </script>
                ";
                } else {
                    $attemp = $result_fetch2['login_otp_attemp'] + 1;
                    echo ($attemp);
                    $updateAttempt = "UPDATE `userdetails` SET `login_otp_attemp`=$attemp WHERE `email`='$_SESSION[user_email]'";
                    mysqli_query($conn, $updateAttempt);
                    echo "
                <script>
                alert('wrong otp');
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
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendmail($email, $v_code)
{
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/SMTP.php';
    global $mail_id;
    global $mail_pass;
    global $mail_refname;

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $mail_id;                     //SMTP username
        $mail->Password   = $mail_pass;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($mail_id, $mail_refname);
        $mail->addAddress($email);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Verification from SubSport';
        $mail->Body    = "Thanks for resistation! Click the link below to verify the email 
        <a href='http://localhost/product/verify.php?email=$email&v_code=$v_code'>Verify</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $errors[] = "Message could not be sent.";
        return false;
    }
}
