<?php
require_once("database.class.php");

class Listing {

  private $identifier;
  private $user_name;
  private $host_id;
  private $accommodation_name;
  private $town;
  private $address;
  private $address_coordinates;
  // represent the capacity of the accomodation
  private $available_space;
  private $potential_guest_max;
  private $accommodation_type;
  private $description;

  // create facilities class or use boolean values ?
  private $facilities_swimming_pool;
  private $facilities_ladies_bar;
  private $facilities_wifi;
  private $facilities_games_room;
  private $facilities_gym;
  private $facilities_braai;
  private $facilities_room_service;
  private $facilities_outdoor_activities;
  private $facilities_restuarant;
  private $tarrif;

  private $extra_pricings_discount;
  private $min_booking_days;
  private $main_picture;
  private $picture_2;
  private $picture_3;
  private $picture_4;
  private $temp_available;

  // type of listing
  private $beach_type;
  private $outdoor_type;
  private $relax_type;
  private $good_food_type;
  private $city_vibe_type;
  private $adventure_type;
  // Information on upcoming bookings

function __construct($userName, $accommodationName, $t, $addr, $addr_coords, $capacity,
  $per_room, $accommodationType, $descr, $im1, $im2, $im3, $im4, $ta, $extra_p_dis,
  $min_book_days) {

  $this->user_name = $userName;
  $this->accommodation_name =  $accommodationName;
  $this->town = $t;
  $this->address = $addr;
  $this->address_coordinates = $addr_coords;
  $this->available_space = $capacity;
  $this->potential_guest_max = $per_room;
  $this->accommodation_type = $accommodationType;
  $this->description = $descr;
  $this->tarrif = $ta;
  $this->extra_pricings_discount = $extra_p_dis;
  $this->min_booking_days = $min_book_days;

  /* to ensure that people can put up a listing with some empty images */
  if ($im1 == NULL) {
    $this->main_picture = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
  } else {
    $this->main_picture = $im1;
  }

  if ($im2 == NULL) {
    $this->picture_2 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
  } else {
    $this->picture_2 = $im2;
  }

  if ($im3 == NULL) {
    $this->picture_3 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
  } else {
    $this->picture_3 = $im3;
  }

  if ($im4 == NULL) {
    $this->picture_4 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
  } else {
    $this->picture_4 = $im4;
  }

  $this->temp_available = 1;
  $this->facilities_swimming_pool = 0;
  $this->facilities_ladies_bar = 0;
  $this->facilities_wifi = 0;
  $this->facilities_games_room = 0;
  $this->facilities_gym = 0;
  $this->facilities_braai = 0;
  $this->facilities_room_service = 0;
  $this->facilities_outdoor_activities = 0;
  $this->facilities_restuarant = 0;
  $this->beach_type = 0;
  $this->outdoor_type = 0;
  $this->relax_type = 0;
  $this->good_food_type = 0;
  $this->city_vibe_type = 0;
  $this->adventure_type = 0;
}

/**
 * Set the identifier
 *
 * @param $p the value to set the identifier to.
 */
public function setUniqueIdentifier($p) {
  return $this->identifier = $p;
}
/* Set the user name of the host that holds this listing */
public function setUserName($p) {
  $this->user_name = $p;
}

/* Set the identifier of the host that holds this listing */
public function setHostID($p) {
  $this->host_id = $p;
}

/* Set the accomodation name of the listing */
public function setAccommodationName($p) {
  $this->accommodation_name = $p;
}

/* Set the town of the accomodation listing */
public function setTown($p) {
  $this->town = $p;
}

/* Set the accomodation address of the listing */
public function setAddress($p) {
  $this->address = $p;
}

/* Set the coordinates of the listing's address */
public function setAddressCoordinates($p) {
  $this->address_coordinates = $p;
}

/* Set the accomodation type for the listing */
public function setAccommodationType($p) {
  $this->accommodation_type = $p;
}

/* Set the capacity of the accommodation */
public function setCapacity($p) {
  $this->available_space = $p;
}

/* Set the per room pontential maximum of the accommodation */
public function setMaxPerRoom($p) {
  $this->potential_guest_max = $p;
}
/* Set the accomodation description of the listing */
public function setDescription($p) {
  $this->description = $p;
}

/**
 * Set the extra pricings discount.
 *
 * @param $p the value to set extra pricings discount to.
 */
public function setExtraPricingsDiscount($p) {
  $this->extra_pricings_discount = $p;
}

/**
 * Set the minimum number of days.
 *
 * @param $p the value to set the minimum number of days.
 */
public function setMinBookingDays($p) {
  $this->min_booking_days = $p;
}

/**
 * Set the main picture.
 *
 * @param $p the value to set the main picture to.
 */
public function setMainPicture($p) {
  if ($p == NULL) {
    $this->main_picture = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
  } else {
    $this->main_picture = $p;
  }
}

/**
 * Set the picture 2.
 *
 * @param $p the value to set the picture 2 to.
 */
public function setPicture2($p) {
  if ($p == NULL) {
    $this->picture_2 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
  } else {
    $this->picture_2 = $p;
  }
}

/**
 * Set the picture 3.
 *
 * @param $p the value to set the picture 3 to.
 */
public function setPicture3($p) {
  if ($p == NULL) {
    $this->picture_3 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
  } else {
    $this->picture_3 = $p;
  }
}

/**
 * Set the picture 4.
 *
 * @param $p the value to set the picture 4 to.
 */
public function setPicture4($p) {
  if ($p == NULL) {
    $this->picture_4 = addslashes(file_get_contents("./assets/images/standardListEmpty.jpg"));
  } else {
    $this->picture_4 = $p;
  }
}

/**
 * Set the Temporary Available status.
 *
 * @param $p the value to temporary available status.
 */
public function setTemporaryAvailable($p) {
  $this->temp_available = $p;
}

/* Set whether the accommodation listing has a swimming pool */
public function setFacilitiesSwimmingPool($p) {
  $this->facilities_swimming_pool = $p;
}

/* Set whether the accommodation listing has a ladies bar */
public function setFacilitiesLadiesBar($p){
  $this->facilities_ladies_bar = $p;
}

/* Set whether the accommodation listing has wifi */
public function setFacilitiesWifi($p){
  $this->facilities_wifi = $p;
}

/* Set whether the accommodation listing has a games room */
public function setFacilitiesGamesRoom($p){
  $this->facilities_games_room = $p;
}

/* Set whether the accommodation listing has a gym */
public function setFacilitiesGym($p){
  $this->facilities_gym = $p;
}

/* Set whether the accommodation listing has braai facilities */
public function setFacilitiesBraai($p){
  $this->facilities_braai = $p;
}

/* Set whether the accommodation listing has a room service */
public function setFacilitiesRoomService($p){
  $this->facilities_room_service = $p;
}

/* Set whether the accommodation listing has outdoor activities */
public function setFacilitiesOutDoorActivities($p){
  $this->facilities_outdoor_activites = $p;
}

/* Set whether the accommodation listing has a restuarant */
public function setFacilitiesRestuarant($p){
  $this->facilities_restuarant = $p;
}

/* Set accommodation tarrif */
public function setTarrif($p){
  $this->tarrif = $p;
}

/* Set whether the accomodation is a beach type */
public function setBeachType($p) {
  $this->beach_type = $p;
}

/* Set whether the accomodation is a outdoor type */
public function setOutdoorType($p) {
  $this->outdoor_type = $p;
}

/* Set whether the accomodation is a relax type */
public function setRelaxType($p) {
  $this->relax_type = $p;
}

/* Set whether the accomodation is a good food type */
public function setGoodFoodType($p) {
  $this->good_food_type = $p;
}

/* Set whether the accomodation is a city vibe type type */
public function setCityVibeType($p) {
  $this->city_vibe_type = $p;
}

/* Set whether the accomodation is an adventure type */
public function setAdventureType($p) {
  $this->adventure_type = $p;
}

/* The following functions allow collection of variables of the class */

/**
 * Returns the id of the listing.
 *
 * @return the unique identifier of the listing
 */
public function getUniqueIdentifier() {
  return $this->identifier;
}

public function getUserName() {
  return $this->user_name;
}

public function getHostID() {
  return $this->host_id;
}

public function getAccommodationName() {
  return $this->accommodation_name;
}

public function getTown() {
  return $this->town;
}

public function getAddress() {
  return $this->address;
}

public function getAddressCoordinates() {
  return $this->address_coordinates;
}

public function getAccommodationType() {
  return $this->accommodation_type;
}

public function getCapacity() {
  return $this->available_space;
}
public function getMaxPerRoom() {
  return $this->potential_guest_max;
}

public function getDescription() {
  return $this->description;
}

public function getExtraPricingsDiscount() {
  return $this->extra_pricings_discount;
}

public function getMinBookingDays() {
  return $this->min_booking_days;
}

public function getMainPicture($rawForm=FALSE) {
  if ($rawForm == TRUE) {
    return $this->main_picture;
  } else {
    return 'data:image/jpeg;base64,'. base64_encode($this->main_picture);
  }
}

public function getPicture2($rawForm=FALSE) {
  if ($rawForm == TRUE) {
    return $this->picture_2;
  } else {
    return 'data:image/jpeg;base64,'. base64_encode($this->picture_2);
  }
}

public function getPicture3($rawForm=FALSE) {
  if ($rawForm == TRUE) {
    return $this->picture_3;
  } else {
    return 'data:image/jpeg;base64,'. base64_encode($this->picture_3);
  }
}

public function getPicture4($rawForm=FALSE) {
  if ($rawForm == TRUE) {
    return $this->picture_4;
  } else {
    return 'data:image/jpeg;base64,'. base64_encode($this->picture_4);
  }
}

public function getTemporaryAvailable() {
  return $this->temp_available;
}

public function getFacilitiesSwimmingPool() {
  return $this->facilities_swimming_pool;
}

public function getFacilitiesLadiesBar(){
  return $this->facilities_ladies_bar;
}

public function getFacilitiesWifi(){
  return $this->facilities_wifi;
}

public function getFacilitiesGamesRoom(){
  return $this->facilities_games_room;
}

public function getFacilitiesGym(){
  return $this->facilities_gym;
}

public function getFacilitiesBraai(){
  return $this->facilities_braai;
}

public function getFacilitiesRoomService(){
  return $this->facilities_room_service;
}

public function getFacilitiesOutDoorActivities(){
  return $this->facilities_outdoor_activities;
}

public function getFacilitiesRestuarant(){
  return $this->facilities_restuarant;
}

public function getTarrif(){
  return $this->tarrif;
}

public function getBeachType() {
  return $this->beach_type;
}

public function getOutdoorType() {
  return $this->outdoor_type;
}

public function getRelaxType() {
  return $this->relax_type;
}

public function getGoodFoodType() {
  return $this->good_food_type;
}

public function getCityVibeType() {
  return $this->city_vibe_type;
}

public function getAdventureType() {
  return $this->adventure_type;
}

public function __toString() {
  return "Host Username: " . $this->user_name .
  "\nAccommodation Name: " . $this->accommodation_name .
  "\nTown/City: " . $this->town .
  "\nAddress: " . $this->address .
  "\nAccommodation Type: " . $this->accommodation_type .
  "\nDescription: ". $this->description .
  "\nTarriff: " . $this->tarrif;
}

}
?>
