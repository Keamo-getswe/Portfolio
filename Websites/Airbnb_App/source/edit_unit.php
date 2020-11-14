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

  $username = $_SESSION["user"];

  /* initial load of page: enter current information of unit */
  if (isset($_POST['unit'])) {
    $_SESSION['unit'] = $_POST['unit'];
    $unit = $db->get_listing($_SESSION['unit']);

    /* get information */
    $name = $unit->getAccommodationName();
    $town = $unit->getTown();
    $address = $unit->getAddress();
    $type = $unit->getAccommodationType();
    $coor = $unit->getAddressCoordinates();
    $available_rooms = $unit->getCapacity();
    $per_room = $unit->getMaxPerRoom();
    $tarrif = $unit->getTarrif();
    $min_booking_days = $unit->getMinBookingDays();
    $discounts = $unit->getExtraPricingsDiscount();
    $description = $unit->getDescription();
    $available = $unit->getTemporaryAvailable();
    $main_pic = $unit->getMainPicture();
    $pic_2 = $unit->getPicture2();
    $pic_3 = $unit->getPicture3();
    $pic_4 = $unit->getPicture4();

  /* submit changes */
} else if (isset($_POST['name'])) {
    $unit = $db->get_listing($_SESSION['unit']);

    $unit->setAccommodationName(sanitize($_POST['name']));
    $unit->setTown(sanitize($_POST['city']));
    $unit->setAddress(sanitize($_POST['address']));
    $unit->setAccommodationType(sanitize($_POST['acctype']));
    $unit->setAddressCoordinates(sanitize($_POST['coor']));
    $unit->setCapacity(sanitize($_POST['capacity']));
    $unit->setMaxPerRoom(sanitize($_POST['maxperroom']));
    $unit->setTarrif(sanitize($_POST['tarrif']));
    $unit->setMinBookingDays(sanitize($_POST['minbookingdays']));
    $unit->setExtraPricingsDiscount(sanitize($_POST['discounts']));
    $unit->setDescription(sanitize($_POST['description']));
    if (isset($_POST['available']) && $_POST['available'] == 0) {
      $unit->setTemporaryAvailable($_POST['available']);
    } else {
      $unit->setTemporaryAvailable(1);
    }
    /* get facilities */
    $unit->setFacilitiesSwimmingPool(0);
    $unit->setFacilitiesLadiesBar(0);
    $unit->setFacilitiesWifi(0);
    $unit->setFacilitiesGamesRoom(0);
    $unit->setFacilitiesGym(0);
    $unit->setFacilitiesBraai(0);
    $unit->setFacilitiesRoomService(0);
    $unit->setFacilitiesOutDoorActivities(0);
    $unit->setFacilitiesRestuarant(0);

    if (!empty($_POST['facilities'])) {
      foreach ($_POST['facilities'] as $f) {
        if ($f == 'pool') {
          $unit->setFacilitiesSwimmingPool(1);
        } else if ($f == 'bar') {
          $unit->setFacilitiesLadiesBar(1);
        } else if ($f == 'wifi') {
          $unit->setFacilitiesWifi(1);
        } else if ($f == 'games') {
          $unit->setFacilitiesGamesRoom(1);
        } else if ($f == 'gym') {
          $unit->setFacilitiesGym(1);
        } else if ($f == 'braai') {
          $unit->setFacilitiesBraai(1);
        } else if ($f == 'room_service') {
          $unit->setFacilitiesRoomService(1);
        } else if ($f == 'outdoor') {
          $unit->setFacilitiesOutDoorActivities(1);
        } else if ($f == 'restaurant') {
          $unit->setFacilitiesRestuarant(1);
        }
      }
    }

    $id = $unit->getHostID();
    /* add the images to the listing */
    $i = 0;
    foreach($_FILES as $fileKey => $fileArray) {
      if (validImage($fileArray) == "correct") {
        if ($i == 0) {
          $fileContent1 = addslashes(file_get_contents($fileArray["tmp_name"]));
          $unit->setMainPicture($fileContent1);
          $i = $i + 1;
        } else if ($i == 1) {
          $fileContent2 = addslashes(file_get_contents($fileArray["tmp_name"]));
          $unit->setPicture2($fileContent2);
          $i = $i + 1;
        } else if ($i == 2) {
          $fileContent3 = addslashes(file_get_contents($fileArray["tmp_name"]));
          $unit->setPicture3($fileContent3);
          $i = $i + 1;
        } else if ($i == 3) {
          $fileContent4 = addslashes(file_get_contents($fileArray["tmp_name"]));
          $unit->setPicture4($fileContent4);
          $i = $i + 1;
        }
      } else {
        $error_reason = validImage($fileArray);
        // returns "correct" if the image is valid error otherwise
        if ($i == 0) {
          $i = $i + 1;
        } else if ($i == 1) {
          $i = $i + 1;
        } else if ($i == 2) {
          $i = $i + 1;
        } else if ($i == 3) {
          $i = $i + 1;
        }
      }
    }
    $unit->setMainPicture(NULL);
    $unit->setPicture2(NULL);
    $unit->setPicture3(NULL);
    $unit->setPicture4(NULL);
    $db->update_listing($unit);

    if (isset($_SESSION['return_reg_listings']) &&
        $_SESSION['return_reg_listings'] == TRUE) {
      $_SESSION["list"] = "listings";
      header("Location: ./list.php");

    } else {
      header("Location: ./index.php");
    }
  }
  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_listing'])) {
    $db->remove_listing($db->get_listing($_SESSION['unit']));
    header("Location: ./index.php");
  }

  $db->disconnect();

  outputHeadContent("Edit Unit");
 ?>

<link rel="stylesheet" href="./assets/style/unit.css" type="text/css">
</head>
<body>
<?php outputNav("search", $loggedIn); ?>

<div class="Host">
  <br>
  <br>
  <h2>Edit your Listing</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
    name="edit_unit_form" method="POST" onsubmit="return validateEditUnit()">
    <label><strong>Name of Accomodation:</strong></label>
    <input type="text" name="name" value="<?php echo $name;?>" required><br><br>
    <label><strong>City/Town</strong></label>
    <input type="text" name="city" value="<?php echo $town;?>" required><br><br>
    <label><strong>Address</strong></label>
    <input type="text" name="address" value="<?php echo $address;?>" required><br><br>
    <label><strong>Coordinates</strong></label>
    <input type="text" name="coor" value="<?php echo $coor;?>" required><br><br>
    <label><strong>Accommodation type</strong></label>
    <select name="acctype" value="<?php echo $type; ?>">
      <option value="hotel" <?php if ($type == "hotel") echo "selected='selected'"; ?>>
        Hotel</option>
      <option value="selfcat" <?php if ($type == "selfcat") echo "selected='selected'"; ?>>
        Self Catering</option>
      <option value="guesthouse" <?php if ($type == "guesthouse") echo "selected='selected'"; ?>>
        Guest House</option>
      <option value="bandb" <?php if ($type == "bandb") {echo "selected='selected'";} ?>>
        B&B</option>
      <option value="airbandb" <?php if ($type == "airbandb") echo "selected='selected'"; ?>>
        Air B&B</option>
      <option value="lodge" <?php if ($type == "lodge") echo "selected='selected'"; ?>>
        Lodge</option>
    </select>
    <br><br>
    <label><strong>Number of available rooms/units</strong></label>
    <input type="number" name="capacity" value="<?php echo $available_rooms;?>"
           min="1" required><br><br>
    <label><strong>Maximum Capacity per Room</strong></label>
    <input type="number" name="maxperroom" value="<?php echo $per_room;?>" min="1"
           required>
    <br><br>
    <label><strong>Minimum length of stay per booking (nights)</strong></label>
    <input type="number" name="minbookingdays" value="<?php echo $min_booking_days;?>"
          min="1" required>
    <br><br>
    <input <?php if (!$unit->getTemporaryAvailable()) echo "checked"; ?>
      type="checkbox" name="available" value="0">
    <label>Set accommodation temporarily unavailable</label><br><br>

    <p><strong>Description</strong></p>
    <textarea rows="9" cols="50" name="description" required><?php echo $description; ?>
    </textarea>
    <br><br>
    <p><strong>Discounts and Specials</strong></p>
    <textarea rows="9" cols="50" name="discounts" required><?php echo $discounts; ?>
    </textarea>
    <br><br>

    <p><strong>Facilities</strong></p><br>
    <input <?php if ($unit->getFacilitiesSwimmingPool() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="pool" id="pool">
    <label for="pool">Swimming Pool</label><br>
    <input <?php if ($unit->getFacilitiesLadiesBar() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="bar" id="bar">
    <label for="bar">Ladies bar</label><br>
    <input <?php if ($unit->getFacilitiesWifi() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="wifi" id="wifi">
    <label for="wifi">Wifi</label><br>
    <input <?php if ($unit->getFacilitiesGamesRoom() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="games" id="games">
    <label for="games">Games Room</label><br>
    <input <?php if ($unit->getFacilitiesGym() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="gym" id="gym">
    <label for="gym">Gym</label><br>
    <input <?php if ($unit->getFacilitiesBraai() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="braai" id="braai">
    <label for="braai">Braai Area</label><br>
    <input <?php if ($unit->getFacilitiesRoomService() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="room_service" id="room_service">
    <label for="room_service">Room Service</label><br>
    <input <?php if ($unit->getFacilitiesOutDoorActivities() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="outdoor" id="outdoor">
    <label for="outdoor">Outdoor Activities</label><br>
    <input <?php if ($unit->getFacilitiesRestuarant() == TRUE) {
      echo "checked";
    } else {
      echo "";
    } ?> type="checkbox" name="facilities[]" value="restaurant" id="restaurant">
    <label for="restaurant">Restaurant</label><br><br>

    <label for="tarrif">Tarrif p/p per night R</label>
    <input type="text" name="tarrif" value="<?php echo $tarrif; ?>"><br><br>

    <div class="edit_images">
    <p><strong>Images</strong></p><br>
    <img src="<?php echo $main_pic; ?>" alt="image"><br><br>
    <p>Update main image</p>
    <input type="file" name="main_img"><br><br>

    <img src="<?php echo $pic_2; ?>" alt="image"><br><br>
    <p>Update image</p>
    <input type="file" name="img_2"><br><br>

    <img src="<?php echo $pic_3; ?>" alt="image"><br><br>
    <p>Update image</p>
    <input type="file" name="img_3"><br><br>

    <img src="<?php echo $pic_4; ?>" alt="image"><br><br>
    <p>Update image</p>
    <input type="file" name="img_4"><br><br>
    </div>

    <input type="submit" onclick="confirmChanges(this.form)" value="Save Changes"><br><br>
  </form>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
    <input type="submit" name="delete_listing" value="Delete listing">
  </form>
</div>
<div>
  <div>
  <h3>Upcoming bookings</h3>
  <?php  $db->upcoming_bookings($_SESSION['unit']); ?>
 </div>
  <h3>Past bookings</h3>
  <?php $db->past_bookings($_SESSION['unit']);?>
</div>
<script src="./assets/scripts/functions.js"></script>
<script type="text/javascript" src="/assets/scripts/validator.js"> </script>
</body>
</html>
