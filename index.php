<?php
include('db.php');
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
  <title>Login</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
  <link rel="stylesheet" />
</head>

<body>
  <div class="container">
    <div class="wrapper">
      <div class="title"><span>Login</span></div>
      <form action="loginaction.php" method="post">
        <div class="row">
          <i class="fas fa-user"></i>
          <input type="text" placeholder="Email" name="email" required />
        </div>
        <div class="row">
          <i class="fas fa-lock"></i>
          <input type="password" placeholder="Password" name="password" required />
        </div>
        <div class="pass"><a href="reset_pass_form.php">Forgot password?</a></div>

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
          <input type="submit" value="Login" />
        </div>
        <div class="signup-link">
          Not a member? <a href="signup.php">Signup now</a>
        </div>
      </form>
      <!-- Include Google reCAPTCHA script -->
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </div>
  </div>
</body>

</html>