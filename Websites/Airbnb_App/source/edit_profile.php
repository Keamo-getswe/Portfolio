<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*-------------------------------------------------*/

require_once("database.class.php");
require_once("authenticate.php");
require_once("functions.php");

$loggedIn = isLoggedIn();

$db = new Database();
$db->connect();

if ($_SESSION['login'] == HOST) {
  $username = $_SESSION['user'];
  $user = $db->get_host($username);

} else if ($_SESSION['login'] == GUEST) {
  $username = $_SESSION['user'];
  $user = $db->get_guest($username);

} else if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['admin_host_username'])) {
  $username = $_POST['admin_host_username'];
  $user = $db->get_host($username);

} else if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['admin_guest_username'])) {
  $username = $_POST['admin_guest_username'];
  $user = $db->get_guest($username);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password_change'])) {
  $newpassword = sanitize($_POST['newpassword']);
  $user->setPassword($newpassword);
  if ($_SESSION['login'] == HOST) {
    $db->update_host($user);
  } else if ($_SESSION['login'] == GUEST) {
    $db->update_guest($user);
  }
}

/* change information on submission */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_info'])) {
  $user->setTitle(sanitize($_POST['title']));
  $user->setFirstName(sanitize($_POST['firstname']));
  $user->setLastName(sanitize($_POST['lastname']));
  $user->setBirthDay(sanitize($_POST['birthday']));
  $user->setGender(sanitize($_POST['gender']));
	$user->setUserName(sanitize($_POST['user']));
  $user->setMobileNum(sanitize($_POST['mobile']));
  $user->setDescription(sanitize($_POST['description']));
  $user->setTown(sanitize($_POST['town']));
  $user->setAddress(sanitize($_POST['address']));

  if ($_SESSION['login'] == HOST) {
    $db->update_host($user);

  } else if ($_SESSION['login'] == GUEST) {
    $db->update_guest($user);
  }

  if (isset($_SESSION['return_reg_hosts']) && $_SESSION['return_reg_hosts'] == TRUE) {
    $_SESSION["list"] = "guests";
    header("Location: ./list.php");

  } else if (isset($_SESSION['return_reg_guests']) && $_SESSION['return_reg_guests'] == TRUE) {
    $_SESSION["list"] = "hosts";
    header("Location: ./list.php");

  } else {
    header("Location: ./profile.php");
  }

} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_password'])) {
  $user->setPassword($_POST['password_change']);
  $db->update($user);
}

/* get user information */
$title = $user->getTitle();
$firstname = $user->getFirstName();
$lastname = $user->getLastName();
$birthday = $user->getBirthDay();
$gender = $user->getGender();
$tel = $user->getMobileNum();
$description = $user->getDescription();
$town = $user->getTown();
$address = $user->getAddress();

$db->disconnect();

outputHeadContent("Edit Profile");
 ?>
	<link rel="stylesheet" type="text/css" href="./assets/style/edit_profile.css">
</head>
<body>

<?php outputNav("profile", $loggedIn); ?>
    <h1> Edit Profile </h1>
		<div class="wrapper">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
        name="edit_profile_form" onsubmit="return validateEditProfile()">
        <select name="title" id="titles">
          <option <?php if ($title == "Mr") echo "selected='selected'"; ?> value="Mr">Mr</option>
          <option <?php if ($title == "Mrs") echo "selected='selected'"; ?> value="Mrs">Mrs</option>
          <option <?php if ($title == "Miss") echo "selected='selected'"; ?> value="Miss">Miss</option>
          <option <?php if ($title == "Sir") echo "selected='selected'"; ?> value="Sir">Sir</option>
          <option <?php if ($title == "Dr") echo "selected='selected'"; ?> value="Dr">Dr</option>
          <option <?php if ($title == "Prof") echo "selected='selected'"; ?> value="Prof">Prof</option>
        </select>
        <label for="first">First Name<label>
        <input type="text" name="firstname" id="first" value="<?php echo $firstname; ?>"><br>
        <label for="last">Last Name<label>
        <input type="text" name="lastname" id="last" value="<?php echo $lastname; ?>"><br>
        <br>

        <label>Town/City</label>
        <input type="text" name="town" value="<?php echo $town; ?>"><br>
        <label>Address</label>
        <input type="text" name="address" value="<?php echo $address; ?>"><br>

        <label for="birthday">Birthday</label>
        <input type="date" name="birthday" id="birthday" value="<?php echo $birthday; ?>"><br>
        <br>
        <label>Gender</label></br>
        <input <?php if ($gender == "female") echo "checked"; ?> type="radio" name="gender" id="female" value="female">
        <label for="female">Female:</label><br>
        <input <?php if ($gender == "male") echo "checked"; ?> type="radio" name="gender" id="male" value="male">
        <label for"male">Male:</label><br>
        <input <?php if ($gender == "other") echo "checked"; ?> type="radio" name="gender" id="other" value="other">
        <label for="other">Other:</label><br>
        <br>

        <label for="mobilenum">Mobile Number (with your code): <label>
        <input type="text" name="mobile" id="mobilenum" value="<?php echo $tel; ?>"><br>
        <label for="username">Email Address<label>
        <input type="text" name="user" id="email" value="<?php echo $username; ?>"><br>
        <br>
        <p><strong>Description</strong><p>
        <textarea rows="5" cols="60" name="description"><?php echo $description; ?></textarea><br>
        <input type="submit" name="submit_info" value="Save changes">
    </form>

    <a class="displayButton">Change Password</a><br><br>
    <div class="visible">
      <form method="POST" name=change_password onsubmit="return validateFormPassword()"
      action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label>Enter new password</label>
        <input type="password" name="newpassword"><br>
        <label>Re-enter new password</label>
        <input type="password" name="newpassword_check"><br>
        <input type="submit" name="password_change" value="Create new Password">
      </form>
    </div>
	</div>
  <script src="./assets/scripts/functions.js"></script>
  <script type="text/javascript" src="/assets/scripts/validator.js"> </script>
</body>
</html>
