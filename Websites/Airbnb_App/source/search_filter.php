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

$listings_to_display = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  /* from search_main -> destination specified */
  if (isset($_POST['destination'])) {
    $destination = strtolower($_POST['destination']);
    sanitize($destination);
    $listings_to_display = $db->get_all_listings_in($destination);
    $_SESSION['listing_to_display'] = $listings_to_display;
  }

  /* from search_main -> catagory specified*/
  else if (isset($_POST['holiday'])) {
    $holiday = strtolower($_POST['holiday']);
    sanitize($holiday);
    $listings_to_display = $db->get_all_listings_of_type($holiday);
  }
  $_SESSION['listing_to_display'] = $listings_to_display;
}

outputHeadContent("Search");
 ?>
 <link rel="stylesheet" href="./assets/style/search_filter_style_main.css">
 <link rel="stylesheet" media='screen and (max-width: 480px)'
       href="./assets/style/search_filter_style_small.css"/>
 <link rel="stylesheet" media='screen and (min-width: 481px)'
       href="./assets/style/search_filter_style_large.css"/>
</head>

<body>
  <?php outputNav("search", $loggedIn); ?>
  <div class="main">
        <div class="container">
          <h1>Search for your perfect getaway destination</h1>
        </div>

        <div class="filter">
          <h3>Filters</h3>
          <form action="./search_filter.php" method="POST">

            <h4>Capacity</h4>
            <label for="capacity">Number of visitors</label>
            <select id="capacity" name="capacity"
              onchange="displayResults('capacity', this.value)">
              <?php for ($i = 1; $i <= 11; $i++) {
                echo "<option value=\"".$i."\">".$i."</option>";
              }
              echo "<option value=\"more\">more</option>";
              ?>
            </select>

            <h4>Price per night</h4>
            <select id="minprice" name="minprice"
              onchange="displayResults('minprice', this.value)">
              <option selected value="min">min</option>
              <?php for ($i = 500; $i <= 2500; $i += 500) {
                echo "<option value=\"".$i."\">".$i."</option>";
              }
              ?>
            </select>
            <select id="maxprice" name="maxprice"
              onchange="displayResults('maxprice', this.value)">
              <?php for ($i = 500; $i <= 2500; $i += 500) {
                echo "<option value=\"".$i."\">".$i."</option>";
              }
              ?>
              <option selected value="max">max</option>
            </select>
            <br>

            <h4>Accomodation Type</h4>
            <input type="radio" id="all" name="accomtype" value="all"
              onchange="displayResults('accomtype', this.value)">
            <label for="all">All</label>
            <br>
            <input type="radio" id="selfcat" name="accomtype" value="selfcat"
              onchange="displayResults('accomtype', this.value)">
            <label for="selfcat">Self Catering</label>
            <br>
            <input type="radio" id="guest" name="accomtype" value="guest"
              onchange="displayResults('accomtype', this.value)">
            <label for="guest">Guest House</label>
            <br>
            <input type="radio" id="bandb" name="accomtype" value="bandb"
              onchange="displayResults('accomtype', this.value)">
            <label for="bandb">Bed and Breakfast</label>
            <br>
            <input type="radio" id="abandb" name="accomtype" value="abandb"
              onchange="displayResults('accomtype', this.value)">
            <label for="abandb">Air B&B</label>
            <br>
            <input type="radio" id="lodge" name="accomtype" value="lodge"
              onchange="displayResults('accomtype', this.value)">
            <label for="lodge">Lodge</label>
            <br>
            <input type="radio" id="hotel" name="accomtype" value="hotel"
              onchange="displayResults('accomtype', this.value)">
            <label for="hotel">Hotel</label>
            <br>

            <h4>Can't do without</h4>
            <input type="checkbox" id="swim" name="nth" value="swim"
              onchange="displayResults('nth', this.value)">
            <label for="swim">Swimming Pool</label>
            <br>
            <input type="checkbox" id="braai" name="nth" value="braai"
              onchange="displayResults('nth', this.value)">
            <label for="braai">Braai Area</label>
            <br>
            <input type="checkbox" id="gym" name="nth" value="gym"
              onchange="displayResults('nth', this.value)">
            <label for="gym">Gym</label>
            <br>
            <input type="checkbox" id="service" name="nth" value="service"
              onchange="displayResults('nth', this.value)">
            <label for="service">Room Service</label>
            <br>
            <input type="checkbox" id="activities" name="nth" value="activities"
              onchange="displayResults('nth', this.value)">
            <label for="activities">Outdoor Activities</label>
            <br>
            <input type="checkbox" id="wfif" name="nth" value="wifi"
              onchange="displayResults('nth', this.value)">
            <label for="wifi">Wifi</label>
            <br>
            <input type="checkbox" id="restaurant" name="nth" value="restaurant"
              onchange="displayResults('nth', this.value)">
            <label for="restaurant">Restaurant</label>
            <br>
            <input type="checkbox" id="games" name="nth" value="games"
              onchange="displayResults('nth', this.value)">
            <label for="games">Games Room</label>
            <br>
            <input type="checkbox" id="bar" name="nth" value="bar"
              onchange="displayResults('nth', this.value)">
            <label for="bar">Ladies Bar</label>
            <br>
            <br>
          </form>
        </div>

        <div id="listings" class="listings">

          <select>
            <option onclick="sortResults('alphabetic')">Alphabetically</option>
            <option onclick="sortResults('low')">Price - low to high</option>
            <option onclick="sortResults('high')">Price - high to low</option>
          </select>

          <div id="filtered_listings">
          <?php if (empty($listings_to_display)) {
            echo "<br><p><em>No listings match your search</em></p>";
          } else {
            foreach($listings_to_display as $listing) {
              if ($listing->getTemporaryAvailable()) {
                $db->ouput_listing_as_search_result($listing);
              }
            }
          }
          $db->disconnect();
          ?>
        </div>
        </div>
      </div>
      <script src="./assets/scripts/functions.js"></script>
    </body>
  </html>
