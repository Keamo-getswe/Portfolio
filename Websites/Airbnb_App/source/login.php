<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*-------------------------------------------------*/

require_once("database.class.php");
require_once("functions.php");
require_once("authenticate.php");

$username = "";
$password = "";
$errorMessageLogin = "";
$MessageForgot = "";

$db = new Database();
$db->connect();
/*
 * Verifies user and returns TRUE if login is successful,
 * else returns false;
 */
function login($username, $password) {

  global $db;

  if ($db->isHost($username)) {
    $host = $db->get_host($username);
    if ($host->getPassword() == $password) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  if ($db->isGuest($username)) {
    $guest = $db->get_guest($username);
    if ($guest->getPassword() == $password) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  if ($db->isAdministrator($username)) {
    $admin = $db->get_administrator($username);
    if ($admin->getPassword() == $password) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
  return FALSE;
}

/************************************
User has submitted credentials
************************************/
if (($_SERVER['REQUEST_METHOD'] == 'POST') && !(isset($_POST['email']))) {
  $username = $_POST["username"];
  $password = $_POST["password"];
  sanitize($username);
  sanitize($password);

  if (login($username, $password)) {
    if ($db->isGuest($username)) {
      session_start();
      $_SESSION['login'] = GUEST;
      $_SESSION['user'] = $username;
      if ($_SESSION['book'] == TRUE) {
        $_SESSION['book'] = FALSE;
        header("Location: ./bookings.php");
        die();
      }
      header("Location: ./index.php");
      die();

    } else if ($db->isHost($username)) {
      session_start();
      $_SESSION['login'] = HOST;
      $_SESSION['user'] = $username;
      if ($_SESSION['book'] == TRUE) {
        $_SESSION['book'] = FALSE;
        header("Location: ./bookings.php");
        die();
      }
      header("Location: ./index.php");
      die();

    } else if ($db->isAdministrator($username)){
      session_start();
      $_SESSION['login'] = ADMIN;
      $_SESSION['user'] = $username;
      header("Location: ./admin_front_page.php");
      die();

    } else {
      $errorMessageLogin = "Login Failed";
      session_start();
      $_SESSION['login'] = "";
      $_SESSION['user'] = "";
    }

  } else {
    $errorMessageLogin = "Login Failed";
    session_start();
    $_SESSION['login'] = "";
    $_SESSION['user'] = "";
  }

/***********************************
User forgot password
************************************/

} else if (($_SERVER['REQUEST_METHOD'] == 'POST')
            && isset($_POST['email'])) {
    $username = $_POST['email'];
    if ($db->isGuest($username) || $db->isHost($username)
          || $db->isAdministrator($username)) {
      $newpassword = substr(md5(rand()), 0, 8);
      $MessageForgot = "Your new password has been sent.";
      $subject = "Forgot Password";
      $body = "Hi, <br><br> Here is your new Password:<br>";
      $body .= "<strong>".$newpassword."</strong>";
      $body .= "<br><br>Thank you for visiting Disa Travels!";
      mail($username, $subject, $body);
      $errorMessageLogin = "Your new password is ".$newpassword;
      $guest = $db->get_guest($username);
      $guest->setPassword($newpassword);
      $db->update_guest($guest);
    } else {
      $MessageForgot = "Username does not exist.";
    }
}
$db->disconnect();

/*********************************
Create ouput for signin page
**********************************/
outputHeadContent("Sign in");?>
<link href="./assets/style/signin_style.css" type="text/css" rel="stylesheet">
</head>

<body>
  <nav>
    <ul>
        <li><a href="./index.php">Home</a></li>
        <li><a href="./about_us.php">About Us</a></li>
     </ul>
  </nav>

  <div class="container">
    <div class="text">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="login_form" method="POST" onsubmit="return validateLogin(0)">
        <br>
        <input type="email" name="username" placeholder="username" required>
        <br>
        <br>
        <input type="password" name="password" placeholder="password" required>
        <br>
        <br>
        <input type="submit" value="Sign In">
        <p><?php echo $errorMessageLogin; ?></p>
      </form>
      <p>Don't have an account?</p>
      <a href="./guest_register.php">Register as Guest</a><br>
      <a href="./host_register.php">Register as Host</a>
      <br>
      <br>
      <a class="displayButton">Forgot your password?</a>
      <br>
      <br>
      <div class="visible">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="forgot_pass" method="POST" onsubmit="return validateLogin(1)">
          <label for="email"><strong>Email</strong>(We will email you a new password)</label>
          <br>
          <a name="email_link"><input type="email" name="email" value="<?php echo $MessageForgot; ?>" required></a>
          <br>
          <br>
          <button type="submit">Send Password</button>
        </form>
      </div>
    </div>
  </div>
  <script src="./assets/scripts/functions.js"></script>
  <script src="/assets/scripts/validator.js"> </script>
</body>
</html>
