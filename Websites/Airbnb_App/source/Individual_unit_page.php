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

/*************************************************************
Read in information to display
**************************************************************/
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['listing_review'])) {
  $unit_id = $_SESSION['unit'];

  $review = $_POST['review'];
  $rating = $_POST['rate'];
  $guest_username = $_SESSION['user'];

  $db->add_review_listing($guest_username, $unit_id, $rating, $review);

} else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['fav'])) {
  $unit_id = $_SESSION['unit'];
  $guest_username = $_SESSION['user'];

  $db->addAsFavourite($guest_username, $unit_id);

} else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['host_review'])) {
  $unit_id = $_SESSION['unit'];
  $unit = $db->get_listing($unit_id);

  $review = $_POST['review'];
  $guest_username = $_SESSION['user'];
  $host_username = $unit->getUserName();

  $db->add_review_host($guest_username, $host_username, 0, $review);
}

else if (isset($_GET['unit'])) {
  $unit_id = $_GET['unit'];
  $_SESSION['unit'] = $unit_id;
}
else {
  $unit_id = $_SESSION['unit'];
}

$listing = $db->get_listing($unit_id);
$username = $listing->getUserName();
$host = $db->get_host($username);
$host_name = $db->getHostNameAsString($host->getUniqueIdentifier());
$number = $host->getMobileNum();

$db->disconnect();

$name = $listing->getAccommodationName();
$town = $listing->getTown();
$address = $listing->getAddress();
$img_main = $listing->getMainPicture();
$img_2 = $listing->getPicture2();
$img_3 = $listing->getPicture3();
$img_4 = $listing->getPicture4();
$description = $listing->getDescription();
$price = $listing->getTarrif();
$max_guest = $listing->getMaxPerRoom();
$available_rooms = $listing->getCapacity();
$discounts = $listing->getExtraPricingsDiscount();
$min_booking_days = $listing->getMinBookingDays();
$coor = $listing->getAddressCoordinates();

$new_listing = $db->create_listing();

/************************************************************
Ouput page
*************************************************************/
outputHeadContent($name);
 ?>

 <link rel="stylesheet" href="./assets/style/unit.css" type="text/css">
</head>
<body>
 <?php outputNav("search", $loggedIn); ?>

  <div class="Hotel">
  <div class="container">
   <h1><?php echo $name; ?></h1>
 </div>
 <h3><?php echo $town; ?></h3>
 <div class="row">
   <div class="Hotel_pics">
     <img src="<?php echo $img_main; ?>" alt="Maties Hotel">
   </div>
   <div class="Hotel_pics">
     <img src="<?php echo $img_2; ?>" alt="Maties Hotel">
   </div>
   <div class="Hotel_pics">
     <img src="<?php echo $img_3; ?>" alt="Maties Hotel">
   </div>
   <div class="Hotel_pics">
     <img src="<?php echo $img_4; ?>" alt="Maties Hotel">
   </div>
 </div>
 <h4>Description</h4>
 <br>
 <p><em><?php echo $description; ?></em></p>
 <br><br>
 <p><strong>Price</strong> R: <?php echo $price; ?> p/p per night</p><br>
 <p><?php echo "There are <strong>".$available_rooms."</strong> available rooms, "; ?></p>
 <p><?php echo "each accommodating up to <strong>".$max_guest."</strong> people."; ?></p>
 <?php if ($min_booking_days > 1) {?>
 <p><?php echo "Bookings will only be possible if the time stayed exceeds ";
          echo $min_booking_days." nights"; ?></p>
      <?php } ?>
 <br>

 <h4> Facilities</h4>
 <ul class="Facilities">
   <?php
   if ($listing->getFacilitiesSwimmingPool()) {
     echo "<li>Swimming Pool</li>";
   } if ($listing->getFacilitiesLadiesBar()) {
     echo "<li>Ladies Bar</li>";
   } if ($listing->getFacilitiesWifi()) {
     echo "<li>Wifi</li>";
   } if ($listing->getFacilitiesGamesRoom()) {
     echo "<li>Games Room</li>";
   } if ($listing->getFacilitiesRestuarant()) {
     echo "<li>Restaurant</li>";
   } if ($listing->getFacilitiesGym()) {
     echo "<li>Gym</li>";
   } if ($listing->getFacilitiesBraai()) {
     echo "<li>Braai</li>";
   } if ($listing->getFacilitiesRoomService()) {
     echo "<li>Room Service</li>";
   } if ($listing->getFacilitiesOutDoorActivities()) {
     echo "<li>Outdoor Activities</li>";
   }
    ?>
 </ul>

 <h4>Location</h4>
 <div class="mapouter">
   <div class="gmap_canvas">
     <iframe id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo
       $coor;?>&t=&z=13&ie=UTF8&iwloc=&output=embed">
     </iframe>
     <a href="https://www.pureblack.de/werbeagentur/"></a>
   </div>
   <p><em>www.google.maps.com</em></p>
 </div>
 <br>

<h4>Check Availability</h4>
  <label>Arrival</label>
  <input type="date" id="arrival" name="arrival" required>
  <label>Departure</label>
  <input type="date" id="depart" name="depart" required>
  <input type="submit" value="check" onclick="checkAvailability()">
<div id="availability_results"></div>

<br>
<br>
<a href="./bookings.php"><button>Make a Booking</button></a>

<?php if ($loggedIn == GUEST) { ?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
 <input type="submit" name="fav" value="Add this to favourites">
</form>
<?php } ?>

 <br>
 <br>

 <h4>Contact the host for further information</h4>
 <p><strong>Name: </strong><?php echo $host_name;?></p>
 <p><strong>Email: </strong><?php echo $username;?></p>
 <p><strong>Mobile Number: </strong><?php echo $number;?></p><br><br>

 <hr>
 <div class="reviews">
   <h5>Reviews and Ratings</h5>
   <br>
   <?php
   $db->connect();
    $reviews = $db->get_review_listings($unit_id);
    foreach ($reviews as $r) {
      $guest_name = $db->getGuestNameAsString($r->getName());
      $review = $r->getReview();
      $stars = $r->getStars();
      ?>
      <p><em><?php echo "\"".$review."\""; ?></em><?php echo " - ".$guest_name; ?></p>
      <img src="./assets/images/<?php echo $stars ?>_stars.png" alt="stars"><br><br>
    <?php }
    $db->disconnect(); ?>
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
      <p>Have something to say? Please review this listing</p><br>
      <textarea rows="9" cols="50" name="review" placeholder="Enter your review here">
      </textarea>
      <br><br>
      <label>Rate</label>
      <input type="number" name="rate" min="1" max="5"><br><br>
      <input type="submit" name="listing_review" value="Submit review">
    </form>

    <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
      <p>Enjoyed your stay? Please review the host</p><br>
      <textarea rows="9" cols="50" name="review" placeholder="Enter your review here">
      </textarea>
      <br><br>
      <input type="submit" name="host_review" value="Submit review">
    </form>

 </div>

 <hr>
 </body>
 <script src="./assets/scripts/functions.js"></script>
</html>
