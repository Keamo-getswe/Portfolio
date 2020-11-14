<?php

require_once("database.class.php");
session_start();

$db = new Database();
$db->connect();

$name = $_GET['name'];
$value = $_GET['value'];
$listings_to_display = $_SESSION['listing_to_display'];

/* filter for min-price */
if ($name == "minprice") {
  if ($value != "min"){
    foreach ($listings_to_display as $listing) {
      if ($listing->getTarrif() < $value) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);
      }
    }
  }
}

/* filter for max price */
if ($name == "maxprice"){
  if ($value != "max"){
    foreach ($listings_to_display as $listing) {
      if ($listing->getTarrif() > $value) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);
      }
    }
  }
}

/* filter for capacity */
if ($name == "capacity"){
  if ($value != "more"){
    foreach ($listings_to_display as $listing) {
      if ($listing->getCapacity()*$listing->getMaxPerRoom() < $value) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);
      }
    }
  } else if ($value == "more") {
    foreach ($listings_to_display as $listing) {
      if ($listing->getCapacity()*$listing->getMaxPerRoom() < 12) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);
      }
    }
  }
}

/* filter for accomodation type */
if ($name == "accomtype") {
    if ($value != "all") {
      foreach ($listings_to_display as $listing) {
        if ($listing->getAccommodationType() != $value) {
          unset($listings_to_display[array_search($listing, $listings_to_display)]);
        }
      }
    }
}

/* filter for facilities */
if ($name == "nth") {
    foreach ($listings_to_display as $listing) {
      if ($value == "swim" && !$listing->getFacilitiesSwimmingPool()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);

      } else if ($value == "braai" && !$listing->getFacilitiesBraai()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);

      } else if ($value == "gym" && !$listing->getFacilitiesBraai()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);

      } else if ($value == "service" && !$listing->getFacilitiesRoomService()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);

      } else if ($value == "activities" && !$listing->getFacilitiesOutDoorActivities()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);

      } else if ($value == "wifi" && !$listing->getFacilitiesWifi()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);

      } else if ($value == "restaurant" && !$listing->getFacilitiesRestuarant()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);

      } else if ($value == "games" && !$listing->getFacilitiesGamesRoom()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);

      } else if ($value == "bar" && !$listing->getFacilitiesLadiesBar()) {
        unset($listings_to_display[array_search($listing, $listings_to_display)]);
      }
    }
}

$_SESSION['listing_to_display'] = $listings_to_display;

if (empty($listings_to_display)) {
  echo "<p><em>No listings match your search</em></p>";
} else {
  foreach($listings_to_display as $listing) {
    $db->ouput_listing_as_search_result($listing);
  }
  $db->disconnect();
}
?>
