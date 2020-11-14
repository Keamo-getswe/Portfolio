<?php

    require_once("listing.class.php");
    require_once("user.class.php");
    require_once("guest.class.php");
    require_once("host.class.php");
    require_once("administrator.class.php");
    require_once("review.class.php");

    define("FALSE", "0");
    define("TRUE", "1");

    define("HOST", "1");
    define("GUEST", "2");
    define("ADMIN", "3");

class Database {

  const DBHOST = 'localhost';
  const DBUSER = 'disatravels';
  const DBPASS = 'SBoil@agt6157';
  const DB = 'disatravels';

  private $conn = NULL;

  /*
   * Connect to the disas database.
   */
  public function connect() {
    $this->conn = new mysqli(self::DBHOST, self::DBUSER, self::DBPASS, self::DB);
    if ($this->conn->connect_error) {
      echo "Failed to connect to MySQL: " . $this->conn->connect_error;
    }
  }

  /*
   * Disconnect from the disas database.
   */
  public function disconnect() {
    $this->conn->close();
    $this->conn = NULL;
  }

  /* Get connection for use in other parts of the program */
  public function get_conn() {
    return $this->conn;
  }
  /***************************
    Add to database
  ***************************/

  /*-----------------------------------------------------------------------
    Add new guest to the database
  -----------------------------------------------------------------------*/
  public function add_guest(Guest $guest) {
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    /* get all the necessary information */
    $title = $guest->getTitle();
    $firstname = $guest->getFirstName();
    $lastname = $guest->getLastName();
    $username = $guest->getUserName();
    $password = $guest->getPassword();
    $town = $guest->getTown();
    $address = $guest->getAddress();
    $birthday = $guest->getBirthday();
    $number = $guest->getMobileNum();
    $gender = $guest->getGender();
    $profilePicture = $guest->getProfilePicture(TRUE);
    $description = $guest->getDescription();

    /*insert new guest into database */
    $sql = "INSERT INTO guests ";
    $sql .= "(title, first_name, last_name, username, password, town, address, ";
    $sql .= "birthday, mobile_number, gender, profile_picture, description)";
    $sql .= "VALUES";
    $sql .= "('".$title."', '".$firstname."', '".$lastname."', '".$username."', '";
    $sql .= $password."', '".$town."', '".$address."', '".$birthday."', '".$number."', '";
    $sql .= $gender."', '".$profilePicture."', \"".$description."\")";

    if (!mysqli_query($this->conn, $sql)) {
      echo mysqli_error($this->conn);
    }

    /* add the profile picture to the database
    $sql_p = "UPDATE guests SET profile_picture=$profilePicture WHERE username =". $username;

    if (!mysqli_query($this->conn, $sql_p)) {
      echo mysqli_error($this->conn);
    }
    */
    /* get guest's new unique id */
    $sql = "SELECT unique_id FROM guests WHERE username = '".$username."'";

    $result = mysqli_query($this->conn, $sql);
    $row = mysqli_fetch_array($result);
    $guest->setUniqueIdentifier($row['unique_id']);

    mysqli_free_result($result);
  } /* add_guest */

  /*---------------------------------------------------------------------
    Add new host to the database
  ---------------------------------------------------------------------*/
    public function add_host(Host $host) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      /* get all the necessary information */
      $title = $host->getTitle();
      $firstname = $host->getFirstName();
      $lastname = $host->getLastName();
      $username = $host->getUserName();
      $password = $host->getPassword();
      $town = $host->getTown();
      $address = $host->getAddress();
      $birthday = $host->getBirthday();
      $number = $host->getMobileNum();
      $gender = $host->getGender();
      $profilePicture = $host->getProfilePicture(TRUE);
      $description = $host->getDescription();

      /*insert new host into database */
      $sql = "INSERT INTO hosts ";
      $sql .= "(title, first_name, last_name, username, password, town, address, ";
      $sql .= "birthday, mobile_number, gender, profile_picture, description)";
      $sql .= " VALUES ";
      $sql .= "('".$title."', '".$firstname."', '".$lastname."', '".$username."', '";
      $sql .= $password."', '".$town."', '".$address."', '".$birthday."', '".$number."', '";
      $sql .= $gender."', '".$profilePicture."', \"".$description."\")";

      if (!mysqli_query($this->conn, $sql)) {
        echo mysqli_error($this->conn);
      }

      /* get guest's new unique id */
      $sql = "SELECT unique_id FROM hosts WHERE username = '".$username."'";
      $result = mysqli_query($this->conn, $sql);
      $row = mysqli_fetch_array($result);
      $host->setUniqueIdentifier($row['unique_id']);
      mysqli_free_result($result);
    } /* add_host */


    /*---------------------------------------------------------------------
      Add new administrator to the database
     --------------------------------------------------------------------*/
    public function add_administrator(Administrator $administrator) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      /* get all the necessary information */
      $title = $administrator->getTitle();
      $firstname = $administrator->getFirstName();
      $lastname = $administrator->getLastName();
      $username = $administrator->getUserName();
      $password = $administrator->getPassword();
      $birthday = $administrator->getBirthday();
      $number = $administrator->getMobileNum();
      $gender = $administrator->getGender();
      $town = $administrator->getTown();
      $address = $administrator->getAddress();
      $profilePicture = $administrator->getProfilePicture(TRUE);
      $description = $administrator->getDescription();

      /*insert new administrator into database */
      $sql = "INSERT INTO administrators ";
      $sql .= "(title, first_name, last_name, username, password, town, address, ";
      $sql .= "birthday, mobile_number, gender, profile_picture, description)";
      $sql .= " VALUES ";
      $sql .= "('".$title."', '".$firstname."', '".$lastname."', '".$username."', '";
      $sql .= $password."', '".$town."', '".$address."', '".$birthday."', '".$number."', '";
      $sql .= $gender."', '".$profilePicture."', \"".$description."\")";

      if (!mysqli_query($this->conn, $sql)) {
        echo mysqli_error($this->conn);
      }
      /* get guest's new unique id */
      $sql = "SELECT unique_id FROM administrators WHERE username = '".$username."'";
      $result = mysqli_query($this->conn, $sql);
      $row = mysqli_fetch_array($result);
      $administrator->setUniqueIdentifier($row['unique_id']);
      mysqli_free_result($result);
    } /* add_administrator */

    /*-------------------------------------------------------------------------
      Add new listing to the database
     --------------------------------------------------------------------------*/
     public function add_listing(Listing $listing) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      /* get host id */
      $host_username = $listing->getUserName();
      $sql = "SELECT unique_id FROM hosts WHERE username = '".$host_username."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        $row = mysqli_fetch_array($result);
        $host_id = $row['unique_id'];
        mysqli_free_result($result);
      }

      /* get all the necessary information */
      $name = $listing->getAccommodationName();
      $category = $listing->getAccommodationType();
      $address = $listing->getAddress();
      $address_coor = $listing->getAddressCoordinates();
      $available_rooms = $listing->getCapacity();
      $room_capacity = $listing->getCapacity();
      $tarrif = $listing->getTarrif();
      $min_booking_days = $listing->getMinBookingDays();
      $description = $listing->getDescription();
      $temp_available = $listing->getTemporaryAvailable();
      $town = $listing->getTown();
      $img_main = $listing->getMainPicture(TRUE);
      $img_2 = $listing->getPicture2(TRUE);
      $img_3 = $listing->getPicture3(TRUE);
      $img_4 = $listing->getPicture4(TRUE);
      $discounts = $listing->getExtraPricingsDiscount();

      /*insert new listing into database */
      $sql = "INSERT INTO listings ";
      $sql .= "(accommodation_name, category, address, address_coordinates,";
      $sql .= "available_rooms, room_capacity, tarrif, host_username, host_id, ";
      $sql .= "min_booking_days, extra_pricings_discount, description, set_temp_available, town)";
      $sql .= " VALUES ";
      $sql .= "(\"".$name."\", '".$category."', '".$address."', '".$address_coor."', '";
      $sql .= $available_rooms."', '".$room_capacity."', '".$tarrif."', '".$host_username."', ";
      $sql .= $host_id.", '".$min_booking_days."', \"".$discounts."\", \"".$description."\", '";
      $sql .= $temp_available."', '".$town."')";

      if (!mysqli_query($this->conn, $sql)) {
        echo mysqli_error($this->conn);
      }

      /* add appropriate facilities to database */
      $swimpool = $listing->getFacilitiesSwimmingPool();
      $bar = $listing->getFacilitiesLadiesBar();
      $wifi = $listing->getFacilitiesWifi();
      $games = $listing->getFacilitiesGamesRoom();
      $gym = $listing->getFacilitiesGym();
      $braai = $listing->getFacilitiesBraai();
      $roomserve = $listing->getFacilitiesRoomService();
      $outdooractv = $listing->getFacilitiesOutDoorActivities();
      $restaurant = $listing->getFacilitiesRestuarant();
      $beach = $listing->getBeachType();
      $outdoor = $listing->getOutdoorType();
      $relax = $listing->getRelaxType();
      $food = $listing->getGoodFoodType();
      $city = $listing->getCityVibeType();
      $adventure = $listing->getAdventureType();

      /* get listing's new unique id */
      $sql = "SELECT unique_id FROM listings WHERE host_username = '".$host_username."'";
      $sql .= "AND accommodation_name = \"".$name."\"";
      $result = mysqli_query($this->conn, $sql);
      $row = mysqli_fetch_array($result);
      $listing->setUniqueIdentifier($row['unique_id']);
      mysqli_free_result($result);

      /*insert facilities and types into database */
      $sql = "INSERT INTO Facilities ";
      $sql .= "(listing_id, swimming_pool, ladies_bar, wifi,";
      $sql .= "games_room, gym, braai_area, room_service, outdoor_activities, ";
      $sql .= "restaurant, beach_type, outdoor_type, relax_type, good_food_type, ";
      $sql .= "city_vibe_type, adventure_type)";
      $sql .= " VALUES ";
      $sql .= "(".$listing->getUniqueIdentifier().", '".$swimpool."', '".$bar."', '".$wifi."', '".$games."', '".$gym."', '";
      $sql .= $braai."', '".$roomserve."', '".$outdooractv."', '".$restaurant."', '";
      $sql .= $beach."', '".$outdoor."', '".$relax."', '".$food."', '".$city."', '".$adventure."')";

      if (!mysqli_query($this->conn, $sql)) {
        echo mysqli_error($this->conn);
      }
    }

/**********************
  Adding Reviews
***********************/
  /*-------------------------------------------------------------------------
    Add new review of listing by guest to the database.
    --------------------------------------------------------------------------*/
    public function add_review_listing($username, $listing_id, $star_rating, $review){
      /*checking for a connecion*/
      if($this->conn == NULL){
        $this->connect();
      }

      /* get the guest id from the database */
      $sql = "SELECT unique_id FROM guests WHERE username = '".$username."'";
      $result = mysqli_query($this->conn, $sql);
      $row = mysqli_fetch_array($result);
      $guest_id = $row['unique_id'];
      mysqli_free_result($result);

      /*inserting into the database*/
      $sql = "INSERT INTO Listings_ratings ";
      $sql .= "(listing_id, guest_id , star_rating, review) VALUES ";
      $sql .= "('".$listing_id."', '".$guest_id."', '".$star_rating."', '".$review."')";

      if (!mysqli_query($this->conn, $sql)) {
        echo mysqli_error($this->conn);
      }

    }

    /*-------------------------------------------------------------------------
      Add new review of host by guest to the database.
      --------------------------------------------------------------------------*/
    public function add_review_host ($username_guest, $username_host ,$star_rating, $review){
      /*checking for a connecion*/
      if($this->conn == NULL){
        $this->connect();
      }

      /* get the guest id  from the database */
      $sql = "SELECT unique_id FROM guests WHERE username = '".$username_guest."'";
      $result = mysqli_query($this->conn, $sql);
      $row = mysqli_fetch_array($result);
      $guest_id = $row['unique_id'];
      mysqli_free_result($result);

      /* get the host id  from the database */
      $sql = "SELECT unique_id FROM hosts WHERE username = '".$username_host."'";
      $result = mysqli_query($this->conn, $sql);
      $row = mysqli_fetch_array($result);
      $host_id = $row['unique_id'];
      mysqli_free_result($result);

      /*inserting into the database*/
      $sql = "INSERT INTO Hosts_ratings";
      $sql .= "(host_id, guest_id , star_ratings, review) VALUES";
      $sql .= "('".$host_id."', '".$guest_id."', '".$star_rating."', '".$review."')";
      if (!mysqli_query($this->conn, $sql)) {
        echo mysqli_error($this->conn);
      }

    }

    /*-------------------------------------------------------------------------
      Add listing as favourite for guest
      --------------------------------------------------------------------------*/
    public function addAsFavourite($username, $unit_id) {
      /*checking for a connecion*/
      if($this->conn == NULL){
        $this->connect();
      }

      /* get the guest id  from the database */
      $sql = "SELECT unique_id FROM guests WHERE username = '".$username."'";
      $result = mysqli_query($this->conn, $sql);
      $row = mysqli_fetch_array($result);
      $guest_id = $row['unique_id'];
      mysqli_free_result($result);

      $sql = "INSERT INTO Favourites";
      $sql .= "(guest_id, list_id) VALUES ";
      $sql .= "('".$guest_id."', '".$unit_id."')";
      if (!mysqli_query($this->conn, $sql)) {
        echo mysqli_error($this->conn);
      }
    }

/***************************
  Remove from database
***************************/

  /*------------------------------------------------------------------
   Remove given guest from the database
  ------------------------------------------------------------------*/
   public function remove_guest(Guest $guest) {
      /*checking for a connecion*/
      if($this->conn == NULL){
        $this->connect();
      }
      $guest_username = $guest->getUserName();
      $sql = "DELETE FROM guests WHERE username = '".$guest_username."'";
      if (!mysqli_query($this->conn, $sql)){
        echo "Error deleting record: ". mysqli_error($this->conn);
      }
    }

  /*------------------------------------------------------------------
    Remove given host from the database
    ------------------------------------------------------------------*/
   public function remove_host(Host $host) {
      if($this->conn == NULL){
        $this->connect();
      }

      $host_username = $host->getUserName();
      $sql = "DELETE FROM hosts WHERE  username = '".$host_username."'";
      if (!mysqli_query($this->conn, $sql)){
        echo "Error deleting record: ". mysqli_error($this->conn);
      }
    }

/*------------------------------------------------------------------
  Remove given administrator from the database
  ------------------------------------------------------------------*/
   public function remove_administrator(Administrator $administrator) {
      if($this->conn == NULL){
        $this->connect();
      }

      $administrator_username = $administrator->getUserName();
      $sql = "DELETE FROM administrators WHERE username = '".$administrator_username."'";
      if (!mysqli_query($this->conn, $sql)){
        echo "Error deleting record: ". mysqli_error($this->conn);
      }
    }

/*------------------------------------------------------------------
  Remove given listing from the database
  ------------------------------------------------------------------*/
   public function remove_listing(Listing $listing) {
      if($this->conn == NULL){
        $this->connect();
      }

      $id = $listing->getUniqueIdentifier();
      $sql = "DELETE FROM listings WHERE unique_id = '".$id."'";
      if (!mysqli_query($this->conn, $sql)){
        echo "Error deleting record: ". mysqli_error($this->conn);
      }

      /*Remove listing from facilities table */
      $sql = "DELETE FROM Facilities WHERE listing_id = '".$id."'";
      if (!mysqli_query($this->conn, $sql)){
        echo "Error deleting record: ". mysqli_error($this->conn);
      }
    }

/****************************
  Get entries from database
*****************************/
/*------------------------------------
  Get host's reviews from the database
  -----------------------------------*/
  public function get_review_host($id){
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    $reviews = array();
    $sql = "SELECT guest_id, review FROM Hosts_ratings ";
    $sql .= "WHERE host_id = '".$id."' LIMIT 5";
    if ($result = mysqli_query($this->conn, $sql)) {
      while ($row = mysqli_fetch_array($result)) {
        $guest_id = $row['guest_id'];
        $review = $row['review'];
        $reviews[] = new Review($guest_id, $review, '0');
      }
      mysqli_free_result($result);
    }
    return $reviews;
  }

  /*----------------------------------------
    Get listing's reviews from the database
    ----------------------------------------*/
  public function get_review_listings($id){
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    $reviews = array();
    $sql = "SELECT guest_id, review, star_rating FROM Listings_ratings ";
    $sql .= "WHERE listing_id = '".$id."' LIMIT 3";
    if ($result = mysqli_query($this->conn, $sql)) {
      while ($row = mysqli_fetch_array($result)) {
        $guest_id = $row['guest_id'];
        $review = $row['review'];
        $stars = $row['star_rating'];
        $reviews[] = new Review($guest_id, $review, $stars);
      }
      mysqli_free_result($result);
    } else {
      echo mysqli_error($this->conn);
    }
    return $reviews;
  }

  /*----------------------------------------
    Get guest's name given id
    ----------------------------------------*/
    public function getGuestNameAsString($id){
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $name = "";
      $sql = "SELECT title, first_name, last_name FROM guests ";
      $sql .= "WHERE unique_id = '".$id."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        $row = mysqli_fetch_array($result);
        $name .= $row['title']." ";
        $name .= $row['first_name']." ";
        $name .= $row['last_name'];
        mysqli_free_result($result);
      }
      return $name;
    }

    /*----------------------------------------
      Get hosts's name given id
      ----------------------------------------*/
      public function getHostNameAsString($id){
        /* check connection */
        if ($this->conn == NULL) {
          $this->connect();
        }

        $name = "";
        $sql = "SELECT title, first_name, last_name FROM hosts ";
        $sql .= "WHERE unique_id = '".$id."'";
        if ($result = mysqli_query($this->conn, $sql)) {
          $row = mysqli_fetch_array($result);
          $name .= $row['title']." ";
          $name .= $row['first_name']." ";
          $name .= $row['last_name'];
          mysqli_free_result($result);
        }
        return $name;
      }

    /*------------------------------------------------------------------
    Get guest from database and return it as a guest object.
    ------------------------------------------------------------------*/
   public function get_guest($username) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $guest = NULL;
      $sql = "SELECT * FROM guests WHERE username = '".$username."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        /* check if the record of the guest actually exists */
        if (mysqli_num_rows($result) == 0) {
          return NULL;
        }

        $row = mysqli_fetch_array($result);

        $title = $row['title'];
        $firstname = $row['first_name'];
        $lastname = $row['last_name'];
        $password = $row['password'];
        $town = $row['town'];
        $address = $row['address'];
        $birthday = $row['birthday'];
        $number = $row['mobile_number'];
        $gender = $row['gender'];
        $profilePicture = $row['profile_picture'];
        $description  = $row['description'];
        $id = $row['unique_id'];

        $guest = new Guest($town, $address, $title, $firstname, $lastname,
                           $birthday, $number, $gender, $username, $password,
                           $profilePicture, $description);
        $guest->setUniqueIdentifier($id);
        mysqli_free_result($result);
      }
      return $guest;
    } /* get_guest */

/*------------------------------------------------------------------
  Get host from database and return it as a host object.
  ------------------------------------------------------------------*/
   public function get_host($username) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $host= NULL;
      $sql = "SELECT * FROM hosts WHERE username = '".$username."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        $row = mysqli_fetch_array($result);

        /* check if the record of the host actually exists */
        if (mysqli_num_rows($result) == 0) {
          return NULL;
        }

        $title = $row['title'];
        $firstname = $row['first_name'];
        $lastname = $row['last_name'];
        $password = $row['password'];
        $town = $row['town'];
        $address = $row['address'];
        $birthday = $row['birthday'];
        $number = $row['mobile_number'];
        $gender = $row['gender'];
        $profilePicture = $row['profile_picture'];
        $description  = $row['description'];
        $id = $row['unique_id'];

        $host = new Host($town, $address, $title, $firstname, $lastname,
                           $birthday, $number, $gender, $username, $password,
                           $profilePicture, $description);
        $host->setUniqueIdentifier($id);
        mysqli_free_result($result);
      }
      return $host;
    } /* get_host */

  /*------------------------------------------------------------------
  Get administrator from database and return it as a administrator
  object.
  ------------------------------------------------------------------*/
   public function get_administrator($username) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $administrator = NULL;
      $sql = "SELECT * FROM administrators WHERE username = '".$username."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        $row = mysqli_fetch_array($result);

        /* check if the record of the administrator actually exists */
        if (mysqli_num_rows($result) == 0) {
          return NULL;
        }

        $title = $row['title'];
        $firstname = $row['first_name'];
        $lastname = $row['last_name'];
        $password = $row['password'];
        $birthday = $row['birthday'];
        $number = $row['mobile_number'];
        $gender = $row['gender'];
        $profilePicture = $row['profile_picture'];
        $description  = $row['description'];
        $id = $row['unique_id'];
        $town = $row['town'];
        $address = $row['address'];

        $administrator = new Administrator($town, $address, $title, $firstname,
                                           $lastname, $birthday, $number, $gender,
                                           $username, $password, $profilePicture,
                                           $description);
        $administrator->setUniqueIdentifier($id);
        mysqli_free_result($result);
      }
      return $administrator;
    } /* get_administrator */

  /*------------------------------------------------------------------
  Get listing from database and return it as a listing object
  ------------------------------------------------------------------*/
   public function get_listing($id) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      /* get the listing */
      $listing = NULL;
      $sql = "SELECT * FROM listings WHERE unique_id = '".$id."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        $row = mysqli_fetch_array($result);

        $name = $row['accommodation_name'];
        $category = $row['category'];
        $town = $row['town'];
        $address = $row['address'];
        $address_coor = $row['address_coordinates'];
        $available_rooms = $row['available_rooms'];
        $capacity = $row['room_capacity'];
        $tarrif = $row['tarrif'];
        $username = $row['host_username'];
        $host_id = $row['host_id'];
        $min_bookings = $row['min_booking_days'];
        if ($row['main_pic'] != NULL) {
          $main_pic = $row['main_pic'];
        } else {
          $main_pic = NULL;
        }

        if ($row['pic_2'] != NULL) {
          $pic_2 = $row['pic_2'];
        } else {
          $pic_2 = NULL;
        }

        if ($row['pic_3'] != NULL) {
          $pic_3 = $row['pic_3'];
        } else {
            $pic_3 = NULL;
        }

        if ($row['pic_4'] != NULL) {
          $pic_4 = $row['pic_4'];
        } else {
            $pic_4 = NULL;
        }
        $discounts = $row['extra_pricings_discount'];
        $available = $row['set_temp_available'];
        $description  = $row['description'];
        $id = $row['unique_id'];

        $listing = new Listing($username, $name, $town, $address, $address_coor,
                               $available_rooms, $capacity, $category, $description,
                               $main_pic, $pic_2, $pic_3, $pic_4, $tarrif, $discounts,
                               $min_bookings);
        $listing->setUniqueIdentifier($id);
        $listing->setTemporaryAvailable($available);
        $listing->setUserName($username);
        mysqli_free_result($result);
      }

      /* set appropriate facilities */
      $sql = "SELECT * FROM Facilities WHERE listing_id = '".$id."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        $row = mysqli_fetch_array($result);
        if ($row['swimming_pool']) {
          $listing->setFacilitiesSwimmingPool(TRUE);
        }
        if ($row['ladies_bar']) {
          $listing->setFacilitiesLadiesBar(TRUE);
        }
        if ($row['wifi']) {
          $listing->setFacilitiesWifi(TRUE);
        }
        if ($row['games_room']) {
          $listing->setFacilitiesGamesRoom(TRUE);
        }
        if ($row['gym']) {
          $listing->setFacilitiesGym(TRUE);
        }
        if ($row['braai_area']) {
          $listing->setFacilitiesBraai(TRUE);
        }
        if ($row['room_service']) {
          $listing->setFacilitiesRoomService(TRUE);
        }
        if ($row['outdoor_activities']) {
          $listing->setFacilitiesOutDoorActivities(TRUE);
        }
        if ($row['restaurant']) {
          $listing->setFacilitiesRestuarant(TRUE);
        }
        if ($row['beach_type']) {
          $listing->setBeachType(TRUE);
        }
        if ($row['outdoor_type']) {
          $listing->setOutdoorType(TRUE);
        }
        if ($row['relax_type']) {
          $listing->setRelaxType(TRUE);
        }
        if ($row['good_food_type']) {
          $listing->setGoodFoodType(TRUE);
        }
        if ($row['city_vibe_type']) {
          $listing->setCityVibeType(TRUE);
        }
        if ($row['adventure_type']) {
          $listing->setAdventureType(TRUE);
        }
        mysqli_free_result($result);
      }

      return $listing;
    } /* get_listing */

  /*------------------------------------------------------------------
  Get all the host's listings from database and return it as an
  array of listings.
------------------------------------------------------------------*/
  public function get_users_listings($username) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      /* build array of user's listings */
      $listing;
      $listings = array();
      $sql = "SELECT unique_id AS id FROM listings WHERE host_username = '".$username."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
            $listing = $this->get_listing($row['id']);
            $listings[] = $listing;
        }
        mysqli_free_result($result);
      }
      return $listings;
    } /* get_users_listings */

  /*************************
    Edit database entries
  **************************/

/*------------------------------------------------------------------
  Update the given guest's information in the database
------------------------------------------------------------------*/
 public function update_guest($guest) {
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    /* get new information */
    $title = $guest->getTitle();
    $firstname = $guest->getFirstName();
    $lastname = $guest->getLastName();
    $town = $guest->getTown();
    $address = $guest->getAddress();
    $birthday = $guest->getBirthday();
    $number = $guest->getMobileNum();
    $gender = $guest->getGender();
    $description  = $guest->getDescription();
    $id = $guest->getUniqueIdentifier();
    $username = $guest->getUserName();
    $password = $guest->getPassword();

    /* update database */
    $sql = "UPDATE guests ";
    $sql .= "SET title='".$title."', first_name='".$firstname."', last_name='";
    $sql .= $lastname."', username='".$username."', town='".$town."', ";
    $sql .= "address='".$address."', birthday='".$birthday."', mobile_number='";
    $sql .= $number."', gender='".$gender."', ";
    $sql .= "description=\"".$description."\", password='".$password."'";
    $sql .= " WHERE unique_id = '".$id."'";

    if (!mysqli_query($this->conn, $sql)) {
      echo mysqli_error($this->conn);
    }
  } /* update_guest */

  /*------------------------------------------------------------------
    Update the given host's information in the database
  ------------------------------------------------------------------*/
   public function update_host($host) {
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    /* get new information */
    $title = $host->getTitle();
    $firstname = $host->getFirstName();
    $lastname = $host->getLastName();
    $town = $host->getTown();
    $address = $host->getAddress();
    $birthday = $host->getBirthday();
    $number = $host->getMobileNum();
    $gender = $host->getGender();
    $description  = $host->getDescription();
    $id = $host->getUniqueIdentifier();
    $username = $host->getUserName();
    $password = $host->getPassword();

    /* update database */
    $sql = "UPDATE hosts ";
    $sql .= "SET title='".$title."', first_name='".$firstname."', last_name='";
    $sql .= $lastname."', username='".$username."', town='".$town."', ";
    $sql .= "address='".$address."', birthday='".$birthday."', mobile_number='";
    $sql .= $number."', gender='".$gender."', ";
    $sql .= "description=\"".$description."\", password='".$password."'";
    $sql .= " WHERE unique_id = '".$id."'";

    if (!mysqli_query($this->conn, $sql)) {
      echo mysqli_error($this->conn);
    }
  } /* update_host */

  /*------------------------------------------------------------------
          Update the given listing's information in the database
  ------------------------------------------------------------------*/
   public function update_listing($listing) {
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    /* get new information */
    $name = $listing->getAccommodationName();
    $category = $listing->getAccommodationType();
    $address = $listing->getAddress();
    $address_coor = $listing->getAddressCoordinates();
    $available_rooms = $listing->getCapacity();
    $room_capacity = $listing->getCapacity();
    $tarrif = $listing->getTarrif();
    $min_bookings = $listing->getMinBookingDays();
    $discounts = $listing->getExtraPricingsDiscount();
    $description  = $listing->getDescription();
    $available = $listing->getTemporaryAvailable();
    $id = $listing->getUniqueIdentifier();

    /* update database */
    $sql = "UPDATE listings ";
    $sql .= "SET accommodation_name=\"".$name."\", category='".$category."', address='";
    $sql .= $address."', address_coordinates='".$address_coor."', available_rooms='".$available_rooms."', ";
    $sql .= "room_capacity='".$room_capacity."', tarrif='".$tarrif."', min_booking_days='";
    $sql .= $min_bookings."', ";
    $sql .= " extra_pricings_discount=\"";
    $sql .= $discounts."\", description=\"".$description."\", set_temp_available='".$available."'";
    $sql .= " WHERE unique_id = '".$id."'";

    if (!mysqli_query($this->conn, $sql)) {
      echo mysqli_error($this->conn);
    }

    /* apdate facilities in database */
    $swimpool = $listing->getFacilitiesSwimmingPool();
    $bar = $listing->getFacilitiesLadiesBar();
    $wifi = $listing->getFacilitiesWifi();
    $games = $listing->getFacilitiesGamesRoom();
    $gym = $listing->getFacilitiesGym();
    $braai = $listing->getFacilitiesBraai();
    $roomserve = $listing->getFacilitiesRoomService();
    $outdooractv = $listing->getFacilitiesOutDoorActivities();
    $restaurant = $listing->getFacilitiesRestuarant();

    /*insert facilities and types into database */
    $sql = "UPDATE Facilities ";
    $sql .= "SET swimming_pool='".$swimpool."', ladies_bar='".$bar."', ";
    $sql .= "wifi='".$wifi."', games_room='".$games."', gym='".$gym."', ";
    $sql .= "braai_area='".$braai."', room_service='".$roomserve."', ";
    $sql .= "outdoor_activities='".$outdooractv."', restaurant='".$restaurant."'";
    $sql .= " WHERE listing_id='".$id."'";

    if (!mysqli_query($this->conn, $sql)) {
      echo mysqli_error($this->conn);
    }
  } /* update_listing */

/*************************
  Other functions
*************************/

/*------------------------------------------------------------------
  Get all the guests that are currently stored in the database.
------------------------------------------------------------------*/
   public function getAllGuests() {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      /* build array of guests */
      $guest;
      $all_guests = array();
      $sql = "SELECT username FROM guests";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
            $guest = $this->get_guest($row['username']);
            $all_guests[] = $guest;
        }
        mysqli_free_result($result);
      }
      return $all_guests;
    } /* getAllGuests */

/*------------------------------------------------------------------
  Get all the hosts that are currently stored in the database.
  ------------------------------------------------------------------*/
 public function getAllHosts() {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      /* build array of hosts */
      $host;
      $all_hosts = array();
      $sql = "SELECT username FROM hosts";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
            $host = $this->get_host($row['username']);
            $all_hosts[] = $host;
        }
        mysqli_free_result($result);
      }
      return $all_hosts;
    } /* getAllHosts */

/*------------------------------------------------------------------
  Get all the administrators that are currently stored in the database.
  ------------------------------------------------------------------*/
   public function getAllAdministrators() {
     /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      /* build array of administrators */
      $administrator;
      $all_administrators = array();
      $sql = "SELECT username FROM administrators";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
            $administrator = $this->get_administrator($row['username']);
            $all_administrators[] = $administrator;
        }
        mysqli_free_result($result);
      }
      return $all_administrators;
    } /* getAllAdministrators */

/*------------------------------------------------------------------
  Get all the listings that are currently stored in the database.
------------------------------------------------------------------*/
   public function getAllListings() {
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    /* build array of listings */
    $listing;
    $listings = array();
    $sql = "SELECT unique_id AS id FROM listings";
    if ($result = mysqli_query($this->conn, $sql)) {
      while ($row = mysqli_fetch_array($result)) {
          $listing = $this->get_listing($row['id']);
          $listings[] = $listing;
      }
      mysqli_free_result($result);
    }
    return $listings;
    } /* getAllListings */

/*------------------------------------------------------------------
  Get the number of guests in the database
  ------------------------------------------------------------------*/
 public function get_num_guests() {
  /* check connection */
  if ($this->conn == NULL) {
    $this->connect();
  }

  $sql = "SELECT COUNT(unique_id) AS num FROM guests";
  if ($result = mysqli_query($this->conn, $sql)) {
    $row = mysqli_fetch_array($result);
    $num_of_guests = $row['num'];
    mysqli_free_result($result);
  }
  return $num_of_guests;
}


/*------------------------------------------------------------------
  Get the number of hosts in the database
------------------------------------------------------------------*/
 public function get_num_hosts() {
  /* check connection */
  if ($this->conn == NULL) {
    $this->connect();
  }

  $sql = "SELECT COUNT(unique_id) AS num FROM hosts";
  if ($result = mysqli_query($this->conn, $sql)) {
    $row = mysqli_fetch_array($result);
    $num_of_hosts = $row['num'];
    mysqli_free_result($result);
  }
  return $num_of_hosts;
}


/*------------------------------------------------------------------
  Get the number of listingsin the database
------------------------------------------------------------------*/
   public function get_num_listings() {
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    $sql = "SELECT COUNT(unique_id) AS num FROM listings";
    if ($result = mysqli_query($this->conn, $sql)) {
      $row = mysqli_fetch_array($result);
      $num_of_listings = $row['num'];
      mysqli_free_result($result);
    }
    return $num_of_listings;
  }

  /*------------------------------------------------------------------
    Add a booking to the database
  ------------------------------------------------------------------*/
  public function booking_made($host_username, $guest_username, $listing_id,
                               $arrive, $depart, $num_adults, $num_children,
                               $account_num, $branch_code, $bank, $price) {
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    /* get host's id */
    $sql = "SELECT unique_id AS id FROM hosts WHERE username = '".$host_username."'";
    if ($result = mysqli_query($this->conn, $sql)) {
      $row = mysqli_fetch_array($result);
      $host_id = $row['id'];
      mysqli_free_result($result);
    }

    /* get guest's id */
    $sql = "SELECT unique_id AS id FROM guests WHERE username = '".$guest_username."'";
    if ($result = mysqli_query($this->conn, $sql)) {
      $row = mysqli_fetch_array($result);
      $guest_id = $row['id'];
      mysqli_free_result($result);
    }

    $sql = "INSERT INTO bookings ";
    $sql .= "(host_id, guest_id, listing_id, arrival_date, departure_date,";
    $sql .= "adult_number, children_number, account_number, branch_code, ";
    $sql .= "bank, total_price) VALUES ";
    $sql .= "('".$host_id."', '".$guest_id."', '".$listing_id."', '".$arrive."',";
    $sql .= " '".$depart."', '".$num_adults."', '".$num_children."', '".$account_num."', ";
    $sql .= "'".$branch_code."', '".$bank."', '".$price."')";
    if (!mysqli_query($this->conn, $sql)) {
      echo mysqli_error($this->conn);
    }

  }

  /*------------------------------------------------------------------
    Get the number of bookings in the database
  ------------------------------------------------------------------*/
   public function get_num_bookings() {
     /* check connection */
     if ($this->conn == NULL) {
       $this->connect();
     }

     $num_of_bookings = 0;
     $sql = "SELECT COUNT(*) AS num FROM bookings";
     if ($result = mysqli_query($this->conn, $sql)) {
       $row = mysqli_fetch_array($result);
       $num_of_bookings = $row['num'];
       mysqli_free_result($result);
     } else {
       echo mysqli_error($this->conn);
     }
     return $num_of_bookings;
  }

/*------------------------------------------------------------------
  Determine if given username is a guest in the database
  ------------------------------------------------------------------*/
   public function isGuest($username) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $sql = "SELECT unique_id FROM guests WHERE username ='".$username."'";
      $result = mysqli_query($this->conn, $sql);
      if (mysqli_num_rows($result)) {
        mysqli_free_result($result);
        return TRUE;
      } else {
        mysqli_free_result($result);
        return FALSE;
      }
    } /* isGuest */

/*------------------------------------------------------------------
  Determine if given username is a host in the database
  ------------------------------------------------------------------*/
   public function isHost($username) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $sql = "SELECT unique_id FROM hosts WHERE username ='".$username."'";
      $result = mysqli_query($this->conn, $sql);
      if (mysqli_num_rows($result)) {
        mysqli_free_result($result);
        return TRUE;
      } else {
        mysqli_free_result($result);
        return FALSE;
      }
    } /* isHost */

/*------------------------------------------------------------------
  Determine if given username is a administrator in the database
  ------------------------------------------------------------------*/
   public function isAdministrator($username) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $sql = "SELECT unique_id FROM administrators WHERE username ='".$username."'";
      $result = mysqli_query($this->conn, $sql);
      if (mysqli_num_rows($result)) {
        mysqli_free_result($result);
        return TRUE;
      } else {
        mysqli_free_result($result);
        return FALSE;
      }
    } /* isAdministrator */

/*------------------------------------------------------------------
  Return new listing object
------------------------------------------------------------------*/
    public function create_listing() {
      $new_listing = new Listing("username", "accommodationName", "town", "address", "address_coordinates", 10,
        2, "lodge", "description", NULL, NULL, NULL, NULL, 1000, "no discount",
        2);
      return $new_listing;
    } /* create_listing */


    /*------------------------------------------------------------------
      Output table of all hosts currently in the database
      ------------------------------------------------------------------*/
   public function table_of_hosts() {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $table = "<table>";
      $table .= "<thead>";
      $table .= "<th>Id</th>";
      $table .= "<th>Name</th>";
      $table .= "<th>Username</th>";
      $table .= "<th>Cell nr</th>";
      $table .= "<th>City/town</th>";
      $table .= "<th>Address</th>";
      $table .= "<th>Gender</th>";
      $table .= "<th>Birthday</th>";
      $table .= "<th>Description</th>";
      $table .= "<th>Edit</th>";
      $table .= "<th>Delete</th>";
      $table .= "</thead>";
      $table .= "<tfoot>";
      $table .= "<tr>";
      $table .= "<td colspan=\"11\"><a href=\"./host_register.php\"><button><img ";
      $table .= "src=\"./assets/images/add_profile.png\" alt=\"add\"></button></a></td>";
      $table .= "</tr>";
      $table .= "</tfoot>";

      /* get all hosts from the database and add to table */
      $sql = "SELECT * FROM hosts";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
          $table .= "<tr>";
          $table .= "<td>".$row['unique_id']."</td>";
          $table .= "<td>".$row['title']." ".$row['first_name']." ".$row['last_name']."</td>";
          $table .= "<td>".$row['username']."</td>";
          $table .= "<td>".$row['mobile_number']."</td>";
          $table .= "<td>".$row['town']."</td>";
          $table .= "<td>".$row['address']."</td>";
          $table .= "<td>".$row['gender']."</td>";
          $table .= "<td>".$row['birthday']."</td>";
          $table .= "<td>".$row['description']."</td>";

          $table .= "<td><form action='./edit_profile.php' method='POST'>";
          $table .= "<button type='submit' name='admin_host_username' value=\"";
          $table .= $row['username']."\">";
          $table .= "<img src=\"./assets/images/edit.png\" alt=\"edit\"></button>";
          $table .= "</form></td>";

          $table .= "<td><form action='./list.php' method='POST'>";
          $table .= "<button type='submit' name='delete_host' value=\"";
          $table .= $row['username']."\">";
          $table .= "<img src=\"./assets/images/delete.png\" alt=\"delete\"></button>";
          $table .= "</form></td>";
          $table .= "</tr>";
        }
        mysqli_free_result($result);

      }
      $table .= "</table>";
      echo $table;
    } /* table_of_hosts */

    /*------------------------------------------------------------------
      Output table of all guests currently in the database
      ------------------------------------------------------------------*/
   public function table_of_guests() {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $table = "<table>";
      $table .= "<thead>";
      $table .= "<th>Id</th>";
      $table .= "<th>Name</th>";
      $table .= "<th>Username</th>";
      $table .= "<th>Cell nr</th>";
      $table .= "<th>City/town</th>";
      $table .= "<th>Address</th>";
      $table .= "<th>Gender</th>";
      $table .= "<th>Birthday</th>";
      $table .= "<th>Description</th>";
      $table .= "<th>Edit</th>";
      $table .= "<th>Delete</th>";
      $table .= "</thead>";
      $table .= "<tfoot>";
      $table .= "<tr>";
      $table .= "<td colspan=\"11\"><a href=\"./guest_register.php\"><button><img ";
      $table .= "src=\"./assets/images/add_profile.png\" alt=\"add\"></button></a></td>";
      $table .= "</tr>";
      $table .= "</tfoot>";

      /* get all hosts from the database and add to table */
      $sql = "SELECT * FROM guests";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
          $table .= "<tr>";
          $table .= "<td>".$row['unique_id']."</td>";
          $table .= "<td>".$row['title']." ".$row['first_name']." ".$row['last_name']."</td>";
          $table .= "<td>".$row['username']."</td>";
          $table .= "<td>".$row['mobile_number']."</td>";
          $table .= "<td>".$row['town']."</td>";
          $table .= "<td>".$row['address']."</td>";
          $table .= "<td>".$row['gender']."</td>";
          $table .= "<td>".$row['birthday']."</td>";
          $table .= "<td>".$row['description']."</td>";

          $table .= "<td><form action='./edit_profile.php' method='POST'>";
          $table .= "<button type='submit' name='admin_guest_username' value=\"";
          $table .= $row['username']."\">";
          $table .= "<img src=\"./assets/images/edit.png\" alt=\"edit\"></button>";
          $table .= "</form></td>";

          $table .= "<td><form action='./list.php' method='POST'>";
          $table .= "<button type='submit' name='delete_guest' value=\"";
          $table .= $row['username']."\">";
          $table .= "<img src=\"./assets/images/delete.png\" alt=\"delete\"></button>";
          $table .= "</form></td>";
          $table .= "</tr>";
        }
        mysqli_free_result($result);
    }
    $table .= "</table>";
    echo $table;
  } /* table_of_guests */


/*------------------------------------------------------------------
  Output table of all listings currently in the database
  ------------------------------------------------------------------*/
   public function table_of_listings() {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $table = "<table>";
      $table .= "<thead>";
      $table .= "<th>Id</th>";
      $table .= "<th>Name</th>";
      $table .= "<th>Category</th>";
      $table .= "<th>Address</th>";
      $table .= "<th>Coordinates</th>";
      $table .= "<th>Nr Rooms/Units</th>";
      $table .= "<th>Capacity per Room/Unit</th>";
      $table .= "<th>Tarrif</th>";
      $table .= "<th>Host</th>";
      $table .= "<th>Min Lenght of Stay</th>";
      $table .= "<th>Discounts</th>";
      $table .= "<th>Description</th>";
      $table .= "<th>Available</th>";
      $table .= "<th>Edit</th>";
      $table .= "<th>Delete</th>";
      $table .= "</thead>";
      $table .= "<tfoot>";
      $table .= "<tr>";
      $table .= "<td colspan=\"15\"><a href=\"./add_unit.php\"><button><img ";
      $table .= "src=\"./assets/images/add_unit.png\" alt=\"add\"></button></a></td>";
      $table .= "</tr>";
      $table .= "</tfoot>";

      /* get all hosts from the database and add to table */
      $sql = "SELECT * FROM listings";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
          $table .= "<tr>";
          $table .= "<td>".$row['unique_id']."</td>";
          $table .= "<td>".$row['accommodation_name']."</td>";
          $table .= "<td>".$row['category']."</td>";
          $table .= "<td>".$row['address']."</td>";
          $table .= "<td>".$row['address_coordinates']."</td>";
          $table .= "<td>".$row['available_rooms']."</td>";
          $table .= "<td>".$row['room_capacity']."</td>";
          $table .= "<td>".$row['tarrif']."</td>";
          $table .= "<td>".$row['host_username']."</td>";
          $table .= "<td>".$row['min_booking_days']."</td>";
          $table .= "<td>".$row['extra_pricings_discount']."</td>";
          $table .= "<td>".$row['description']."</td>";
          if ($row['set_temp_available']) {
            $table .= "<td>Yes</td>";
          } else {
            $table .= "<td>No</td>";
          }
          $table .= "<td><form action=\"./edit_unit.php\" method=\"POST\">";
          $table .= "<button type=\"submit\" name=\"unit\" value=\"";
          $table .= $row['unique_id']."\">";
          $table .= "<img src=\"./assets/images/edit.png\" alt=\"edit\"></button>";
          $table .= "</form></td>";

          $table .= "<td><form action='./list.php' method='POST'>";
          $table .= "<button type='submit' name='delete_listing' value=\"";
          $table .= $row['unique_id']."\">";
          $table .= "<img src=\"./assets/images/delete.png\" alt=\"delete\"></button>";
          $table .= "</form></td>";
          $table .= "</tr>";
        }
        mysqli_free_result($result);
    }
    $table .= "</table>";
    echo $table;
  } /* table_of_listings */

  public function history_table($username){
    /* check connection */

    if ($this->conn == NULL) {
      $this->connect();
    }
    $table = "<table>";
    $table .= "<thead>";
    $table .= "<th>Accommodation</th>";
    $table .= "<th>Host</th>";
    $table .= "<th>Arrival Date</th>";
    $table .= "<th>Departure Date</th>";
    $table .= "<th>Adult Number</th>";
    $table .= "<th>Children Number</th>";
    $table .= "<th>Account Number</th>";
    $table .= "<th>Branch Code</th>";
    $table .= "<th>Bank</th>";
    $table .= "<th>Total Price</th>";
    $table .= "</thead>";
    $table .= "<tfoot>";
    $table .= "<tr>";
    $table .= "</tr>";
    $table .= "</tfoot>";

    /* get the guest id from the database */
    $sql = "SELECT unique_id FROM guests WHERE username = '".$username."'";
    $result = mysqli_query($this->conn, $sql);
    $row = mysqli_fetch_array($result);
    $guest_id = $row['unique_id'];

    $sql = "SELECT * FROM bookings WHERE guest_id = '".$guest_id."'";

    if ($result = mysqli_query($this->conn, $sql)){
      while ($row = mysqli_fetch_array($result)) {
        $listing = $this->get_listing($row['listing_id']);
        $acc_name = $listing->getAccommodationName();
        $host_name = $this->getHostNameAsString($row['host_id']);

        $table .= "<tr>";
        $table .= "<td>".$acc_name."</td>";
        $table .= "<td>".$host_name."</td>";
        $table .= "<td>".$row['arrival_date']."</td>";
        $table .= "<td>".$row['departure_date']."</td>";
        $table .= "<td>".$row['adult_number']."</td>";
        $table .= "<td>".$row['children_number']."</td>";
        $table .= "<td>".$row['account_number']."</td>";
        $table .= "<td>".$row['branch_code']."</td>";
        $table .= "<td>".$row['bank']."</td>";
        $table .= "<td>".$row['total_price']."</td>";
      }
        mysqli_free_result($result);
    }
      $table .= "</table>";
      echo $table;
  }/*history_table*/
  /*---------------------------------------------------
    Displays table with the upcoming bookings for a user
    --------------------------------------------------*/

  public function upcoming_bookings($listing_id){
    /*check connection*/

    if ($this->conn == NULL) {
      $this->connect();
    }

    $table = "<table>";
    $table .= "<thead>";
    $table .= "<th>Guest</th>";
    $table .= "<th>Arrival Date</th>";
    $table .= "<th>Departure Date</th>";
    $table .= "<th>Adult Number</th>";
    $table .= "<th>Children Number</th>";
    $table .= "<th>Account Number</th>";
    $table .= "<th>Branch Code</th>";
    $table .= "<th>Bank</th>";
    $table .= "<th>Total Price</th>";
    $table .= "</thead>";
    $table .= "<tfoot>";
    $table .= "<tr>";
    $table .= "</tr>";
    $table .= "</tfoot>";

    $sql = "SELECT * FROM bookings WHERE listing_id = '".$listing_id."' AND departure_date < CURDATE()";

    if ($result = mysqli_query($this->conn, $sql)){
      while ($row = mysqli_fetch_array($result)) {
        $guest_name = $this->getGuestNameAsString($row['guest_id']);

        $table .= "<tr>";
        $table .= "<td>".$guest_name."</td>";
        $table .= "<td>".$row['arrival_date']."</td>";
        $table .= "<td>".$row['departure_date']."</td>";
        $table .= "<td>".$row['adult_number']."</td>";
        $table .= "<td>".$row['children_number']."</td>";
        $table .= "<td>".$row['account_number']."</td>";
        $table .= "<td>".$row['branch_code']."</td>";
        $table .= "<td>".$row['bank']."</td>";
        $table .= "<td>".$row['total_price']."</td>";
      }
        mysqli_free_result($result);
    }
    $table .= "</table>";
    echo $table;
  }/*upcoming bookings*/

    public function past_bookings($listing_id) {
      /*check connection*/
      if ($this->conn == NULL) {
        $this->connect();
      }
      $table = "<table>";
      $table .= "<thead>";
      $table .= "<th>Guest</th>";
      $table .= "<th>Arrival Date</th>";
      $table .= "<th>Departure Date</th>";
      $table .= "<th>Adult Number</th>";
      $table .= "<th>Children Number</th>";
      $table .= "<th>Account Number</th>";
      $table .= "<th>Branch Code</th>";
      $table .= "<th>Bank</th>";
      $table .= "<th>Total Price</th>";
      $table .= "</thead>";
      $table .= "<tfoot>";
      $table .= "<tr>";
      $table .= "</tr>";
      $table .= "</tfoot>";

      $sql = "SELECT * FROM bookings WHERE listing_id = '".$listing_id."' AND departure_date > CURDATE()";

      if ($result = mysqli_query($this->conn, $sql)){

        while ($row = mysqli_fetch_array($result)) {
          $guest_name = $this->getGuestNameAsString($row['guest_id']);

          $table .= "<tr>";
          $table .= "<td>".$guest_name."</td>";
          $table .= "<td>".$row['arrival_date']."</td>";
          $table .= "<td>".$row['departure_date']."</td>";
          $table .= "<td>".$row['adult_number']."</td>";
          $table .= "<td>".$row['children_number']."</td>";
          $table .= "<td>".$row['account_number']."</td>";
          $table .= "<td>".$row['branch_code']."</td>";
          $table .= "<td>".$row['bank']."</td>";
          $table .= "<td>".$row['total_price']."</td>";
        }
          mysqli_free_result($result);
      }
      $table .= "</table>";
      echo $table;

    }/*past bookings*/

/*------------------------------------------------------------------
  Output the listing returned as result from search
  ------------------------------------------------------------------*/
   public function ouput_listing_as_search_result($listing) {
      $name = $listing->getAccommodationName();
      $img = $listing->getMainPicture();
      $description = $listing->getDescription();
      $price = $listing->getTarrif();
      $id = $listing->getUniqueIdentifier();

      $output = "<section>";
      $output .= "<form action=\"./Individual_unit_page.php\" method=\"GET\">";
      $output .= "<button type=\"submit\" name=\"unit\" value=\"".$id."\"><h4>".$name."</h4></button></form>";
      $output .= "<img src=\"".$img."\" alt=\"".$name."\">";
      $output .= "<div class=\"unit_description\">";
      $output .= "<p>".$description."</p>";
      $output .= "<p><strong>Price: </strong>R".$price." p/p per night</p>";
      $output .= "</div>";
      $output .= "</section>";

      echo $output;
    }

  /*---------------------------------------------------------------------------
    Search functionality
  ----------------------------------------------------------------------------*/

/*------------------------------------------------------------------
  Return array of all listings located in specified town/city
  ------------------------------------------------------------------*/
  public function get_all_listings_in($town) {
    /* check connection */
    if ($this->conn == NULL) {
      $this->connect();
    }

    $listings = array();
    $sql = "SELECT unique_id AS id FROM listings WHERE town = '".$town."'";
    $sql .= "ORDER BY accommodation_name, tarrif";
    if ($result = mysqli_query($this->conn, $sql)) {
      while ($row = mysqli_fetch_array($result)) {
        $listings[] = $this->get_listing($row['id']);
      }
    }
    mysqli_free_result($result);
    return $listings;
  }

  /*------------------------------------------------------------------
    Return array of all listings of specified type
    ------------------------------------------------------------------*/
    public function get_all_listings_of_type($type) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $listings = array();
      $sql = "SELECT listing_id AS id FROM Facilities WHERE ".$type." = '1'";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
          $listings[] = $this->get_listing($row['id']);
        }
      } else {
        echo mysqli_error($this->conn);
      }
      mysqli_free_result($result);
      return $listings;
    }

    /*-----------------------------------------------------------------------
      check if listing is available
     -----------------------------------------------------------------------*/
     public function isAvailable($unit_id, $start, $end) {
       /* check connection */
       if ($this->conn == NULL) {
         $this->connect();
       }

       $sql = "SELECT arrival_date, departure_date FROM bookings ";
       $sql .= "WHERE listing_id = '".$unit_id."'";
       echo "<script>console.log(".$sql.")</script>";
       if ($result = mysqli_query($this->conn, $sql)) {
         while($row = mysqli_fetch_array($result)) {
           echo "<script>console.log('atl least one row')</script>";
           if ($start >= $row['arrival_date'] && $start <= $row['departure_date']) {
             mysqli_free_result($result);
             return FALSE;
           }
           if ($end >= $row['arrival_date'] && $end <= $row['departure_date']) {
             mysqli_free_result($result);
             return FALSE;
           }
           if ($start <= $row['arrival_date'] && $end >= $row['departure_date']) {
             mysqli_free_result($result);
             return FALSE;
           }
         }
         mysqli_free_result($result);
       }
       return TRUE;
     }

     /*------------------------------------------------------------------------
      Output guests Favourites
      ------------------------------------------------------------------------*/
    public function output_favourites($id) {
      /* check connection */
      if ($this->conn == NULL) {
        $this->connect();
      }

      $output = "<h4>Your Favourites</h4>";
      $sql = "SELECT list_id FROM Favourites WHERE guest_id='".$id."'";
      if ($result = mysqli_query($this->conn, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
          $unit_id = $row['list_id'];
          $unit = $this->get_listing($unit_id);
          $unit_name = $unit->getAccommodationName();

          $output .= "<a href='./Individual_unit_page.php?unit=".$unit_id."'>";
          $output .= $unit_name."</a><br>";
        }
        mysqli_free_result($result);
      }

      echo $output."<br><br>";
    }


     /*-----------------------------------------------------------------------
       Make backup of database
      -----------------------------------------------------------------------*/
      public function backup() {
        /* check connection */
        if ($this->conn == NULL) {
          $this->connect();
        }

        //backup administrators table
        $backup_file = "./assets/sql/administrators.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM administrators";
        if (!mysqli_query($this->conn, $sql)) {
          echo mysqli_error($this->conn);
        }

        /* backup  table */
        $backup_file = "./assets/sql/bookings.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM bookings";
        if (!mysqli_query($this->conn, $sql)) {
          echo $this->conn->mysqli_error();
        }

        /* backup  table */
        $backup_file = "./assets/sql/Facilities.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM Facilities";
        if (!mysqli_query($this->conn, $sql)) {
          echo $this->conn->mysqli_error();
        }

        /* backup  table */
        $backup_file = "./assets/sql/Favourites.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM Favourites";
        if (!mysqli_query($this->conn, $sql)) {
          echo $this->conn->mysqli_error();
        }

        /* backup  table */
        $backup_file = "./assets/sql/guests.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM guests";
        if (!mysqli_query($this->conn, $sql)) {
          echo $this->conn->mysqli_error();
        }


        /* backup  table */
        $backup_file = "./assets/sql/hosts.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM hosts";
        if (!mysqli_query($this->conn, $sql)) {
          echo $this->conn->mysqli_error();
        }

        /* backup  table */
        $backup_file = "./assets/sql/Hosts_ratings.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM Hosts_ratings";
        if (!mysqli_query($this->conn, $sql)) {
          echo $this->conn->mysqli_error();
        }

        /* backup  table */
        $backup_file = "./assets/sql/listings.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM listings";
        if (!mysqli_query($this->conn, $sql)) {
          echo $this->conn->mysqli_error();
        }

        /* backup  table */
        $backup_file = "./assets/sql/Listings_ratings.sql";
        $sql = "SELECT * INTO OUTFILE '".$backup_file."' FROM Listings_ratings";
        if (!mysqli_query($this->conn, $sql)) {
          echo $this->conn->mysqli_error();
        }
      }

  /*-----------------------------------------------------------------------
    Make backup of database
  -----------------------------------------------------------------------*/
  public function initialize() {
    $this->conn = new mysqli(DBHOST, DBUSER, DBPASS);
    $sql = "CREATE DATABASE disatravels";
    mysqli_query($this->conn, $sql);

    $sql = "CREATE TABLE 'administrators' (";
    $sql .= "'unique_id' int(11) NOT NULL,";
    $sql .= "'town' varchar(255) NOT NULL,";
      $sql .= "'title' varchar(255) NOT NULL,";
      $sql .= "'first_name' varchar(255) NOT NULL,";
      $sql .= "'last_name' varchar(255) NOT NULL,";
      $sql .= "  'birthday' date NOT NULL,";
      $sql .= "  'gender' varchar(255) NOT NULL,";
      $sql .= "  'username' varchar(255) NOT NULL,";
      $sql .= "  'password' varchar(255) NOT NULL,";
      $sql .= "  'profile_picture' longblob,";
      $sql .= "  'address' text NOT NULL,";
      $sql .= "  'description' text NOT NULL,";
      $sql .= "  'mobile_number' varchar(255) NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);

      $sql = "CREATE TABLE 'bookings' (";
        $sql .= "'unique_id' int(11) NOT NULL,";
        $sql .= "'host_id' int(11) NOT NULL,";
        $sql .= "'guest_id' int(11) NOT NULL,";
        $sql .= "'listing_id' int(11) NOT NULL,";
        $sql .= "'arrival_date' date NOT NULL,";
        $sql .= "'departure_date' date NOT NULL,";
        $sql .= "'adult_number' int(11) NOT NULL,";
        $sql .= "'children_number' int(11) NOT NULL,";
        $sql .= "'account_number' varchar(255) NOT NULL,";
        $sql .= "'branch_code' varchar(255) NOT NULL,";
        $sql .= "'bank' varchar(255) NOT NULL,";
        $sql .= "'total_price' int(11) NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);

      $sql = "CREATE TABLE 'Facilities' (";
        $sql .= "'listing_id' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'swimming_pool' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'ladies_bar' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'wifi' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'games_room' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'gym' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'braai_area' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'room_service' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'outdoor_activities' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'restaurant' int(11) NOT NULL DEFAULT '0',";
        $sql .= "'beach_type' int(1) NOT NULL DEFAULT '0',";
        $sql .= "'outdoor_type' int(1) NOT NULL DEFAULT '0',";
        $sql .= "'relax_type' int(1) NOT NULL DEFAULT '0',";
        $sql .= "'good_food_type' int(1) NOT NULL DEFAULT '0',";
        $sql .= "'city_vibe_type' int(1) NOT NULL DEFAULT '0',";
        $sql .= "'adventure_type' int(1) NOT NULL DEFAULT '0',";
        $sql .= "'unique_id' int(11) NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);

      $sql = "CREATE TABLE 'Favourites' (";
        $sql .= "'guest_id' int(11) NOT NULL,";
        $sql .= "'list_id' int(11) NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);

      $sql = "CREATE TABLE 'guests' (";
        $sql .= "'unique_id' int(11) NOT NULL,";
        $sql .= "'title' varchar(255) NOT NULL,";
        $sql .= "'first_name' varchar(255) NOT NULL,";
        $sql .= "'last_name' varchar(255) NOT NULL,";
        $sql .= "'username' varchar(255) NOT NULL,";
        $sql .= "'password' varchar(255) NOT NULL,";
        $sql .= "'town' varchar(255) NOT NULL,";
        $sql .= "'address' text NOT NULL,";
        $sql .= "'birthday' date NOT NULL,";
        $sql .= "'mobile_number' varchar(255) NOT NULL,";
        $sql .= "'gender' varchar(255) NOT NULL,";
        $sql .= "'profile_picture' longblob,";
        $sql .= "'description' text NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);

      $sql = "CREATE TABLE 'hosts' (";
        $sql .= "'unique_id' int(11) NOT NULL,";
        $sql .= "'town' varchar(255) NOT NULL,";
        $sql .= "'address' text NOT NULL,";
        $sql .= "'title' varchar(255) NOT NULL,";
        $sql .= "'first_name' varchar(255) NOT NULL,";
        $sql .= "'last_name' varchar(255) NOT NULL,";
        $sql .= "'birthday' date NOT NULL,";
        $sql .= "'mobile_number' varchar(255) NOT NULL,";
        $sql .= "'gender' varchar(255) NOT NULL,";
        $sql .= "'username' varchar(255) NOT NULL,";
        $sql .= "'password' varchar(255) NOT NULL,";
        $sql .= "'profile_picture' longblob,";
        $sql .= "'description' text NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);

      $sql = "CREATE TABLE 'Hosts_ratings' (";
        $sql .= "'unique_id' int(11) NOT NULL,";
        $sql .= "'host_id' int(11) NOT NULL,";
        $sql .= "'guest_id' int(11) NOT NULL,";
        $sql .= "'star_ratings' int(11) NOT NULL,";
        $sql .= "'review' text NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);

      $sql = "CREATE TABLE 'listings' (";
        $sql .= "'unique_id' int(11) NOT NULL,";
        $sql .= "'accommodation_name' varchar(255) NOT NULL,";
        $sql .= "'category' varchar(255) NOT NULL,";
        $sql .= "'address' text NOT NULL,";
        $sql .= "'address_coordinates' varchar(255) NOT NULL,";
        $sql .= "'available_rooms' int(11) NOT NULL,";
        $sql .= "'room_capacity' int(11) NOT NULL,";
        $sql .= "'tarrif' int(11) NOT NULL,";
        $sql .= "'host_username' varchar(255) NOT NULL,";
        $sql .= "'host_id' int(11) NOT NULL,";
        $sql .= "'min_booking_days' int(11) NOT NULL,";
        $sql .= "'main_pic' longblob,";
        $sql .= "'pic_2' longblob,";
        $sql .= "'pic_3' longblob,";
        $sql .= "'pic_4' longblob,";
        $sql .= "'extra_pricings_discount' varchar(255) NOT NULL,";
        $sql .= "'description' varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,";
        $sql .= "'set_temp_available' int(1) NOT NULL,";
        $sql .= "'town' varchar(255) NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);

      $sql = "CREATE TABLE 'Listings_ratings' (";
        $sql .= "'unique_id' int(11) NOT NULL,";
        $sql .= "'listing_id' int(11) NOT NULL,";
        $sql .= "'guest_id' int(11) NOT NULL,";
        $sql .= "'star_rating' int(11) NOT NULL,";
        $sql .= "'review' text NOT NULL";
      $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1";
      mysqli_query($this->conn, $sql);
  }
}
?>
