<?php
require_once("database.class.php");

 abstract class User {
		protected $identifier;
    protected $town;
    protected $address;

		// user_type can be "guest", "host", "admininstrator"
		protected $title;
		protected $first_name;
    protected $last_name;

		// YYYY-MM-DD
		protected $birth_day;
		protected $mobile_num;
		protected $gender;
		protected $username;
		protected $password;
    protected $profile_picture;
    protected $description;

		public function __construct($tow, $addr, $ti, $firstName, $lastName, $birthDay, $mobileNum, $gen, $user_name, $pass, $profilePicture, $descr) {
      $this->town = $tow;
      $this->address = $addr;
      $this->title = $ti;
			$this->first_name = $firstName;
    	$this->last_name = $lastName;
			$this->birth_day = $birthDay;
			$this->mobile_num = $mobileNum;
			$this->gender = $gen;
			$this->username = $user_name;
			$this->password = $pass;

      if ($profilePicture == NULL) {
        $this->profile_picture = addslashes(file_get_contents("./assets/images/noProfilePicture.jpg"));
      } else {
        $this->profile_picture = $profilePicture;
      }
      $this->description = $descr;
		}

		/* Set identifier */
		public function setUniqueIdentifier($p) {
			$this->identifier = $p;
		}

    /* Set the town of the user */
    public function setTown($p) {
      $this->town = $p;
    }
    /* Set the address of the user */
    public function setAddress($p) {
      $this->address = $p;
    }

		/* Set identifier */
		public function setUserType($p) {
			$this->user_type = $p;
		}

		/* Set the title of the individual, Mr or Mrs etc */
		public function setTitle($p) {
			$this->title = $p;
    }

		/* Set the first name of the user */
		public function setFirstName($p) {
			$this->first_name = $p;
    }

		/* Set the last name of the user */
		public function setLastName($p) {
			$this->last_name = $p;
    }

		/* Set the birthday of the user */
		public function setBirthDay($p) {
			$this->birth_day = $p;
    }

		/* Set the mobile number of the user */
		public function setMobileNum($p) {
			$this->mobile = $p;
    }

		/* Set the gender of the user */
		public function setGender($p) {
			$this->gender = $p;
    }

		/* Set the username of the user */
		public function setUserName($p) {
			$this->username = $p;
    }

		/* Set the password of the user */
		public function setPassword($p) {
			$this->password = $p;
    }

    /* Set the profie picture of the user */
    public function setProfilePicture($p) {
      if ($p == NULL) {
        $this->profile_picture = addslashes(file_get_contents("./assets/images/noProfilePicture.jpg"));
      } else {
        $this->profile_picture = $p;
      }
    }

    /* Set the description of this user */
    public function setDescription($p) {
      $this->description = $p;
    }

		/* the following functions can be used to get specific variables relating to this class */

    /* Returns the town of the user */
    public function getTown() {
      return $this->town;
    }
    /* Returns the address of the user */
    public function getAddress() {
      return $this->address;
    }

		public function getUniqueIdentifier() {
			return $this->identifier;
		}

		/*public function getUserType() {
			return $this->user_type;
		}*/

		public function getTitle() {
			return $this->title;
    }

		public function getFirstName() {
			return $this->first_name;
    }

		public function getLastName() {
			return $this->last_name;
    }

		public function getBirthDay() {
			return $this->birth_day;
    }

		public function getMobileNum() {
			return $this->mobile_num;
    }

		public function getGender() {
			return $this->gender;
    }

		public function getUserName() {
			return $this->username;
    }

		public function getPassword() {
			return $this->password;
    }

    /* Returns the profie picture of the user */
    public function getProfilePicture($rawForm=FALSE) {

      if ($rawForm == TRUE) {
         return base64_encode($this->profile_picture);
      } else {
        return 'data:image/jpeg;base64,'. base64_encode($this->profile_picture);
      }
    }

    /* Returns the description of this user */
    public function getDescription() {
      return $this->description;
    }

		public function __toString() {
			return "Title:" . $this->title .
			"\nFirst name: " . $this->first_name .
			"\nLast name: " . $this->last_name .
			"\nBirthday: " . $this->birth_day .
			"\nMobile Number: " . $this->mobile_num .
			"\nGender: " . $this->gender .
			"\nEmail(Username): " . $this->username .
			"\nPassword: " . $this->password .
      "\nTown: " . $this->town .
      "\nAddress: " . $this->address .
      "\nProfile Picture: " . $this->profile_picture .
      "\nDescription: " . $this->description;
		}
	}
?>
