<?php
include('db.php');
include('mail_detail.php');
session_start();

?>

<?php
include('db.php');
$errors = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $recaptcha_secret = '6LeMtS0pAAAAAC4MZuKD8CQk1EIFFuQdsgB20P_6';
    $recaptcha_response = $_POST['g-recaptcha-response'];

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result);

    if ($response->success) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        // SQL query to check if the user exists with the provided email and password
        $sql = $conn->prepare("SELECT * FROM userdetails WHERE email = ?");
        $sql->bind_param("s", $email);

        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows == 1) {
            // Fetching the user's data
            $row = $result->fetch_assoc();

            // Verifying the password
            if (password_verify($password, $row['password'])) {

                if ($row['is_varified'] == 0) {
                    echo "<script>
                alert('Your account has not varifyed. please varify through the mail we have sent.');
                window.location.href='index.php';
                </script>";
                } else {
                    $v_code = random_int(100000, 999999);
                    $sql2 = $conn->prepare("UPDATE `userdetails` SET `login_otp`=? WHERE `email`=?");

                    $sql2->bind_param("is", $v_code, $email);

                    if ($sql2->execute() && send_otp_mail($email, $v_code)) {
                        session_start();
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['user_email'] = $row['email'];
                        $_SESSION['role'] = $row['role'];
                        header("Location: otp_page.php"); // Redirect to a success page
                        exit();
                    } else {
                        $errors[] = "Error: Something went wrong.";
                    }
                    $sql->close();
                }
            } else {
                // Password is incorrect
                $errors[] = "Password is incorrect";
            }
        } else {
            // No user found with the provided email
            $errors[] = "User not found";
        }

        // Close the prepared statement and database connection
        $sql->close();
        $conn->close();
    } else {
        $errors[] = "Invalid reCaptcha.";
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_otp_mail($email, $v_code)
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
        $mail->Subject = 'OTP from SubSport.';
        $mail->Body    = "$v_code this is login otp.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $errors[] = "Message could not be sent.";
        return false;
    }
}
