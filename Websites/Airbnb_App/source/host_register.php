<?php
/*
 * Author: Ibrahim Sheriff Sururu
 *
 * This handles the registration of a host ouputting the valid form to be
 * filled for registration and handling validation and addition to the database.
 */
require_once("register.php");
require_once("database.class.php");
require_once("authenticate.php");
require_once("functions.php");

$log_status = isLoggedIn();
$db = new Database();
$db->connect();

/* hidden message that shows up only if registration fails */
$registration_error = "";

 if (($_SERVER["REQUEST_METHOD"] == "POST") && !(isset($_POST["email"]))) {
   $location = $_POST["location"];
   $location = sanitize($location);
   $address = $_POST["address"];
   $address = sanitize($address);
   $title = $_POST["title"];
   $title = sanitize($title);
   $first_name = $_POST["firstname"];
   $first_name = sanitize($first_name);
   $last_name = $_POST["lastname"];;
   $last_name = sanitize($last_name);
   $birthday = $_POST["birthday"];
   $birthday = sanitize($birthday);
   $mobile_num = $_POST["mobilenum"];
   $mobile_num = sanitize($mobile_num);
   $gender = $_POST["gender"];
   $gender = sanitize($gender);
   // use filter to validate the email
   $username = $_POST["username"];
   $password1 = $_POST["password1"];
   $password1 = sanitize($password1);
   $password2 = $_POST["password2"];
   $password2 = sanitize($password2);
   $descriptionProperty = $_POST["description_accommodation"];
   $descriptionProperty = sanitize($descriptionProperty);
   $hostDescription = $_POST["description"];
   $hostDescription = sanitize($hostDescription);
   $tarrif = $_POST["tarrif"];
   $tarrif = sanitize($tarrif);

   /* ensure that a user cannot sign up with another email already in the system */
   $temp_host_status = !$db->isGuest($username) && !$db->isHost($username) && !$db->isAdministrator($username);

   if (filter_var($username, FILTER_VALIDATE_EMAIL) && $temp_host_status
      && ($password1 === $password2) && (validImageFile("fileToUpload") == "correct")) {
     /* handles successful registration if the form inputs are valid */
     $fileContent = addslashes(file_get_contents($_FILES["fileToUpload"]["tmp_name"]));
     $newHost = new Host($location, $address, $title, $first_name, $last_name, $birthday, $mobile_num, $gender, $username, $password1, $fileContent, $hostDescription);

     $db->add_host($newHost);

     $_SESSION['login'] = HOST;
     $_SESSION['user'] = $username;
     header("Location: ./add_unit.php");
     die();
   } else {
     if (!$temp_host_status) {
       /* means that the host username is already taken */
       $registration_error = "Invalid inputs. Your email is already linked to an account";
     } else if ($password1 !== $password2) {
       /* means that the password provided was not re-typed again correctly */
       $registration_error = "Invalid inputs. Your passwords do not match";
     } else if (validImageFile("fileToUpload") != "correct") {
       /* means that there was an attempt to upload an invalid image */
       $registration_error = "Invalid inputs. " . validImageFile("fileToUpload");
     } else {
       $registration_error = "Invalid inputs. Registration Failed";
     }
     session_destroy();
     session_start();
     $_SESSION['login'] = "";
     $_SESSION['user'] = "";
   }
}

$db->disconnect();

outputHeadContent("Register as Host");
?>
<link type="text/css" rel="stylesheet" href="./assets/style/host_register.css" />
</head>
<body>
<div class="wrapper">
<?php
  outputNav("hostreg", $log_status);
  /* adds marketing information specific to the host */
  registerTop("host");
  ?>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="register"
    id="register" onsubmit="return validateRegistration()"
    enctype="multipart/form-data" method="POST">
    <?php
  registerFormBody();
  echo "<h4>". $registration_error . "</h4>";
  ?>
</div>
<script type="text/javascript" src="/assets/scripts/validator.js"> </script>
</body>
</html>
