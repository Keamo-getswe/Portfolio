<?php

require_once("database.class.php");

$db = new Database();

session_start();

function sort_alpha($a, $b) {
  $x = strtolower($a->getAccommodationName());
  $y = strtolower($b->getAccommodationName());
  if ($x < $y) {return -1;}
  if ($x > $y) {return 1;}
  return 0;
}

function sort_low($a, $b) {
  return $a->getTarrif()-$b->getTarrif();
}

function sort_high($a, $b) {
  return $b->getTarrif()-$a->getTarrif();
}

$type = $_GET['type'];
$listings_to_display = $_SESSION['listing_to_display'];

/* sort aphabetically */
if ($type == "alphabetic") {
  usort($listings_to_display, "sort_alpha");
}

/* sort price low to high */
else if ($type == "low") {
  usort($listings_to_display, "sort_low");
}

/* sort price high to low */
else if ($type == "high") {
  usort($listings_to_display, "sort_high");
}

if (empty($listings_to_display)) {
  echo "<br><p><em>No listings match your search</em></p>";
} else {
  foreach($listings_to_display as $listing) {
    $db->ouput_listing_as_search_result($listing);
  }
}
?>
