<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['login_status'])) {
    if ($_SESSION['login_status'] == "true") {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SignUp</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        $(document).ready(function() {
            $("#password").on("keyup", function() {
                var password = $(this).val();
                var strength = 0;
                if (password.length >= 8) {
                    strength += 1;
                }
                if (password.match(/([a-z])/)) {
                    strength += 1;
                }
                if (password.match(/([A-Z])/)) {
                    strength += 1;
                }
                if (password.match(/([0-9])/)) {
                    strength += 1;
                }
                if (password.match(/([!@#$%^&*()])/)) {
                    strength += 1;
                }
                if (strength >= 1) {
                    const passwordStrength = document.getElementById('password_strength');
                    passwordStrength.style.paddingLeft = '60px';
                }
                if (strength == 1) {
                    $("#password_strength").html("very");
                    $("#password_strength").css("color", "dark red");
                } else if (strength == 2) {
                    $("#password_strength").html("Weak");
                    $("#password_strength").css("color", "red");
                } else if (strength == 3) {
                    $("#password_strength").html("Moderate");
                    $("#password_strength").css("color", "orange");
                } else if (strength == 4) {
                    $("#password_strength").html("Strong");
                    $("#password_strength").css("color", "green");
                } else if (strength == 5) {
                    $("#password_strength").html("Very Strong");
                    $("#password_strength").css("color", "darkgreen");
                } else {
                    $("#password_strength").html("");
                }
            });
            $("#cpassword").on("keyup", function() {
                const passwordMatch = document.getElementById('password_match');
                passwordMatch.style.paddingLeft = '60px';
                if ($(this).val() == $("#password").val()) {
                    $("#password_match").html("Passwords match");
                    $("#password_match").css("color", "green");
                } else {
                    $("#password_match").html("Passwords do not match");
                    $("#password_match").css("color", "red");
                }
            });
        });
    </script>
</head>

<body>
    <div class="containesignup">
        <div class="wrapper">
            <div class="title"><span>SignUp</span></div>
            <form action="signupaction.php" method="post">
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Name" name="name" required />
                </div>
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Country" name="country" required />
                </div>
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="email" placeholder="Email" name="email" required />
                </div>
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Favourite Sport" name="favourite_sport" required />
                </div>
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Contact" name="contact" required />
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" name="password" id="password" required />
                </div>
                <span id="password_strength"></span>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Conform Password" name="cpassword" id="cpassword" required />
                </div>
                <span id="password_match"></span>
                <div class="g-recaptcha" data-sitekey="6LeMtS0pAAAAAEhHhwWTTXO4NnBoJ0VYCtX9qd14"></div>
                <div style="color:red">
                    <?php
                    if (isset($_SESSION['errors'])) {
                        foreach ($_SESSION['errors'] as $error) {
                            echo "<p><b>*</b>  $error</p>";
                        }
                        unset($_SESSION['errors']);
                    }
                    ?>
                </div>
                <div class="row button">
                    <input type="submit" value="SignUp" />
                </div>
            </form>
        </div>
    </div>
</body>

</html>