<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*-------------------------------------------------*/
  require_once("database.class.php");
  require_once("authenticate.php");
  require_once("functions.php");

  $logged = isLoggedin();

  $db = new Database();
  $db->connect();

  $unit = $db->get_listing($_SESSION['unit']);

  if ($logged == HOST) {
   $username = $_SESSION["user"];
   $user = $db->get_host($username);
  } elseif ($logged == GUEST) {
    $username = $_SESSION["user"];
    $user = $db->get_guest($username);
  } elseif ($logged == ADMIN) {
    $username = $_SESSION["user"];
    $user = $db->get_administrator($username);
  } else {
    $_SESSION['book'] = TRUE;
    header('Location: ./login.php');
    die();
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host_username = $unit->getUserName();
    $guest_username = $_SESSION['user'];
    $arrive = $_POST['arriving'];
    $depart = $_POST['departing'];
    $num_adults = $_POST['number_of_adults'];
    $num_children = $_POST['number_of_children'];
    $acc_number = $_POST['account_number'];
    $branch_code = $_POST['branch_code'];
    $bank = $_POST['bank'];
    $datediff = ($depart - $arrive)/(60*60*24);
    $price = $unit->getTarrif()*$datediff;
    $unit_id = $unit->getUniqueIdentifier();

    $db->booking_made($host_username, $guest_username, $unit_id, $arrive,
                      $depart, $num_adults, $num_children, $acc_number, $branch_code,
                      $bank, $depart - $arrive);
  }

  $db->disconnect();

  outputHeadContent("Unit");
 ?>
   <link rel="stylesheet" href="./assets/style/unit.css" type="text/css">
 </head>
   <body class="booking">
     <?php outputNav("search", $logged); ?>

     <br>
     <div class="bookings_container">
     <h2> Booking Details </h2>
     <br>
     <p> Please make sure the information below is correct to secure your booking.</p>
     <br>
     <br>

     <form method="post" name="bookings_form">
       <label for="arrival">Date of arrival:</label>
       <input id="arrival" type="date" name="arriving">
       <br>
       <label for="departure">Date of departure:</label>
       <input type="date" name="departing">
       <br>
       <label>Adults:</label>
       <br>
       <input type="number" name="number_of_adults" min="0" required>
       <br>

       <label>Children:</label>
       <br>
       <input type="number" name="number_of_children" min="0" required>
       <br>

        <h4>Personal Information</h4>
        <label>First Name:</label>
        <br>
        <input type="text" name="firstname"
          value="<?php echo $user->getFirstName();?>" required>
        <br>

        <label>Last Name:</label>
        <br>
        <input type="text" name="lastname"
          value="<?php echo $user->getLastName();?>" required>
        <br>

        <label>email Adress:</label>
        <br>
        <input type="text" name="emailaddress"
          value="<?php echo $user->getUserName();?>" required>
        <br>

        <label>Phone Number:</label>
        <br>
        <input type="text" name="phonenumber"
          value="<?php echo $user->getMobileNum();?>" required>
        <br>

        <label>Residential Address:</label>
        <br>
        <input type="text" name="resAddress"
          value="<?php echo $user->getAddress(); ?>" required>
        <br>

        <h4>Banking Details</h4>
        <figure>
          <img src="./assets/images/visa.png">
          <img src="./assets/images/mastercard.png">
          <img src="./assets/images/paypal.png" >
          <img src="./assets/images/discover.png">
        </figure>
        <label>Account Number:</label>
        <br>
        <input type="text" name="account_number" required>
        <br>

        <label>Bank:</label>
        <br>
        <input type="text" name="bank" required>
        <br>

        <label>Branch Code:</label>
        <br>
          <input type="text" name="branch_code" required>
         <br>
         <br>
         <button type="submit" onclick="validateBookings(<?php
            echo $unit->getMinBookingDays(); ?>)">Make Booking!</button>
       </form>
     </div>
    <script type="text/javascript" src="./assets/scripts/validator.js"></script>
   </body>
 </html>
