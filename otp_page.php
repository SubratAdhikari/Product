<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OTP</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" />
</head>

<body>
    <div class="containesignup">
        <div class="wrapper">
            <div class="title"><span>OTP</span></div>
            <form action="login_otp_verify.php" method="post">
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="OTP" name="otp" required />
                </div>
                <div class="row button">
                    <input type="submit" value="Verify" />
                </div>
            </form>
        </div>
    </div>
</body>
<?php
session_start();

?>

</html>