
<?php
include('db.php');
include('mail_detail.php');
session_start();
$captcha_v = false;
if (isset($_SESSION['user_id']) && isset($_SESSION['login_status'])) {
    if ($_SESSION['login_status'] == "true") {
        header("Location: dashboard.php");
        exit();
    }
}

$errors = array();

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
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Varification from SubSport.';
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
        $captcha_v = true;
    } else {
        $captcha_v = false;
        $errors[] = "Captcha verification failed!";
    }


    $name = htmlspecialchars($_POST['name']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $incpassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $country = htmlspecialchars($_POST['country']);
    $contact = preg_replace('/[^0-9]/', '', $_POST['contact']);
    $favourite_sport = $_POST['favourite_sport'];

    if (empty($name) || empty($password) || empty($contact) || empty($email) || empty($country)) {
        $errors[] = "All fields are required.";
    }
    if ($password != $cpassword) {
        $errors[] = "Password does not match";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if (!$email) {
        $errors[] = "Invalid email country.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one Small letter.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one Capital letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one Number.";
    }
    if (!preg_match('/[!@#$%^&*()]/', $password)) {
        $errors[] = "Password must contain at least one Special Character.";
    }
    if (empty($errors)) {
        $query2 = "SELECT * FROM `userdetails` WHERE `email`='$email'";
        $result2 = mysqli_query($conn, $query2);
        if (mysqli_num_rows($result2) > 0) {
            $errors[] = "email already exist";
        } else {

            $v_code = bin2hex(random_bytes(16));
            $is_varified = 0;
            // Use prepared statements to prevent SQL injection
            $sql = $conn->prepare("INSERT INTO userdetails (name, country, email, contact, password, varification_code, is_varified,sport) 
                           VALUES (?,?,?,?,?,?,?,?)");

            $sql->bind_param("ssssssis", $name, $country, $email, $contact, $incpassword, $v_code, $is_varified, $favourite_sport);


            if ($sql->execute() && sendmail($email, $v_code)) {
                header("Location: index.php"); // Redirect to a success page
                exit();
            } else {
                $errors[] = "Error: Something went wrong.";
            }
            $sql->close();
        }
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: signup.php');
        exit;
    }
}

$conn->close();
