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

  $new_listing = $db->create_listing();
  $msg = "";

  /*********************************************************
  Create new lsting with info submitted
  *********************************************************/
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_listing->setUserName($_SESSION['user']);
    $new_listing->setAccommodationName(sanitize($_POST['name']));
    $new_listing->setTown(sanitize($_POST['city']));
    $new_listing->setAddress(sanitize($_POST['address']));
    $new_listing->setAccommodationType(sanitize($_POST['unit_type']));
    $new_listing->setAddressCoordinates(sanitize($_POST['coor']));
    $new_listing->setCapacity(sanitize($_POST['capacity']));
    $new_listing->setMaxPerRoom(sanitize($_POST['maxperroom']));
    $new_listing->setTarrif(sanitize($_POST['tarrif']));
    $new_listing->setMinBookingDays(sanitize($_POST['minbookingdays']));
    $new_listing->setExtraPricingsDiscount(sanitize($_POST['discounts']));
    $new_listing->setDescription(sanitize($_POST['description']));
    if (isset($_POST['available'])) {
      $new_listing->setTemporaryAvailable(sanitize($_POST['available']));
    }

    if (!empty($_POST['facilities'])) {
        foreach ($_POST['facilities'] as $selected) {
          if ($selected == "pool") {
            $new_listing->setFacilitiesSwimmingPool(TRUE);
          } if ($selected == "bar") {
            $new_listing->setFacilitiesLadiesBar(TRUE);

          } if ($selected == "wifi") {
            $new_listing->setFacilitiesWifi(TRUE);

          } if ($selected == "games") {
            $new_listing->setFacilitiesGamesRoom(TRUE);

          } if ($selected == "gym") {
            $new_listing->setFacilitiesGym(TRUE);

          }if ($selected == "braai") {
            $new_listing->setFacilitiesBraai(TRUE);

          } if ($selected == "room_service") {
            $new_listing->setFacilitiesRoomService(TRUE);

          } if ($selected == "outdoor") {
            $new_listing->setFacilitiesOutDoorActivities(TRUE);

          } if ($selected == "restaurant") {
            $new_listing->setFacilitiesRestuarant(TRUE);
          }
        }
      }

      $i = 0;
      foreach($_FILES as $fileKey => $fileArray) {
        if (validImage($fileArray) == "correct") {
          if ($i == 0) {
            $fileContent1 = addslashes(file_get_contents($fileArray["tmp_name"]));
            $new_listing->setMainPicture($fileContent1);
            $i = $i + 1;
          } else if ($i == 1) {
            $fileContent2 = addslashes(file_get_contents($fileArray["tmp_name"]));
            $new_listing->setPicture2($fileContent2);
            $i = $i + 1;
          } else if ($i == 2) {
            $fileContent3 = addslashes(file_get_contents($fileArray["tmp_name"]));
            $new_listing->setPicture3($fileContent3);
            $i = $i + 1;
          } else if ($i == 3) {
            $fileContent4 = addslashes(file_get_contents($fileArray["tmp_name"]));
            $new_listing->setPicture4($fileContent4);
            $i = $i + 1;
          }
        } else {
          $error_reason = validImage($fileArray); // returns "correct" if the image is valid error otherwise
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
      $new_listing->setMainPicture(NULL);
      $new_listing->setPicture2(NULL);
      $new_listing->setPicture3(NULL);
      $new_listing->setPicture4(NULL);
    $db->add_listing($new_listing);

    if(isset($_SESSION['return_reg_listings']) && $_SESSION['return_reg_listings'] == TRUE) {
      $_SESSION['return_reg_listings'] = FALSE;
      $_SESSION["list"] = "listing";
      header("Location: ./list.php");

    } else {
      header("Location: ./index.php");
    }
  }

  $db->disconnect();

  /***************************************************
  Ouput form
  ***************************************************/
outputHeadContent("Unit - Add Host");
 ?>

<link rel="stylesheet" href="./assets/style/unit.css" type="text/css">
</head>
<body>
 <?php outputNav("search", $loggedIn); ?>

<div class="Host">
  <br>
  <br>
  <h2>Add a new Listing</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="add_unit_form"
    method="POST" enctype="multipart/form-data" onsubmit="return validateAddUnit()">
    <label for="unit_name"><strong>Name of Accommodation:</strong></label>
    <input type="text" name="name" required><br><br>
    <label><strong>City/Town</strong></label>
    <input type="text" name="city" required><br><br>
    <label><strong>Address</strong></label>
    <input type="text" name="address" required><br><br>
    <label><strong>Coordinates</strong></label>
    <input type="text" name="coor" required><br><br>
    <label><strong>Accommodation type</strong></label>
    <select name="unit_type">
      <option value="hotel">Hotel</option>
      <option value="selfcat">Self Catering</option>
      <option value="guesthouse">Guest House</option>
      <option value="bandb">B&B</option>
      <option value="airbandb">Air B&B</option>
      <option value="lodge">Lodge</option>
    </select>
    <br><br>
    <label><strong>Number of available rooms/units</strong></label>
    <input type="number" name="capacity" min="1" required><br><br>
    <label><strong>Maximum Capacity per Room</strong></label>
    <input type="number" name="maxperroom" min="1" required><br><br>
    <label><strong>Minimum length of stay per booking (nights)</strong></label>
    <input type="number" name="minbookingdays" min="1" required><br><br>
    <input type="checkbox" name="available" value="0">
    <label>Set accommodation temporarily unavailable</label><br><br>

    <p><strong>Description</strong></p>
    <textarea name="description" rows="9" cols="50" placeholder="Please add description"></textarea>
    <br><br>
    <p><strong>Discounts and Specials</strong></p>
    <textarea rows="9" cols="50" name="discounts" required></textarea>
    <br><br>

    <p><strong>Image</strong></p>

    <input type="file" name="main_img" id="main_img"><br><br>
    <input type="file" name="img_2" id="img_2"><br><br>
    <input type="file" name="img_3" id="img_3"><br><br>
    <input type="file" name="img_4" id="img_4"><br><br>

    <p><strong>Facilities</strong></p><br>
    <input type="checkbox" name="facilities[]" value="pool" id="pool">
    <label for="pool">Swimming Pool</label><br>
    <input type="checkbox" name="facilities[]" value="bar" id="bar">
    <label for="bar">Ladies bar</label><br>
    <input type="checkbox" name="facilities[]" value="wifi" id="wifi">
    <label for="wifi">Wifi</label><br>
    <input type="checkbox" name="facilities[]" value="games" id="games">
    <label for="games">Games Room</label><br>
    <input type="checkbox" name="facilities[]" value="gym" id="gym">
    <label for="gym">Gym</label><br>
    <input type="checkbox" name="facilities[]" value="braai" id="braai">
    <label for="braai">Braai Area</label><br>
    <input type="checkbox" name="facilities[]" value="room_service" id="room_service">
    <label for="room_service">Room Service</label><br>
    <input type="checkbox" name="facilities[]" value="outdoor" id="outdoor">
    <label for="outdoor">Outdoor Activities</label><br>
    <input type="checkbox" name="facilities[]" value="restaurant" id="restaurant">
    <label for="restaurant">Restaurant</label><br><br>

    <label for="tarrif">Tarrif p/p per night R</label>
    <input type="text" name="tarrif"><br><br>
    <input type="submit" onclick="confirmChanges(this.form)" value="Add">
  </form>

</div>
<script type="text/javascript" src="./assets/scripts/functions.js"></script>
<script type="text/javascript" src="./assets/scripts/validator.js"></script>
</body>
</html>
