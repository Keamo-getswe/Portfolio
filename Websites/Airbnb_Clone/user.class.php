<?php
abstract class User {

	protected $identifier;
	protected $title;
	protected $firstName;
    protected $lastName;
    protected $username;
    protected $password;

    protected $strAddress;
    protected $suburb;
    protected $town;

	//YYYY-MM-DD
	protected $birthday;
	protected $mobileNumber;
    protected $gender;
    protected $description;
    protected $profilePicture;

	public function __construct($title, $firstName, $lastName, $username, $password, $strAddress, $suburb, $town, $birthday, $mobileNumber, $gender, $description, $profilePicture) {
		$this->identifier = -1;
		$this->title = $title;
		$this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
		$this->password = $password;
        $this->strAddress = $strAddress;
		$this->suburb = $suburb;
		$this->town = $town;
		$this->birthday = $birthday;
		$this->mobileNumber = $mobileNumber;
		$this->gender = $gender;
        $this->description = $description;

      	if ($profilePicture == NULL) {
        	$this->profilePicture = addslashes(file_get_contents("./assets/images/noProfilePicture.jpg"));
      	} else {
        	$this->profilePicture = $profilePicture;
		}
	}

    /* Getter Functions */

	public function getIdentifier() {
		return $this->identifier;
	}

	public function getTitle() {
		return $this->title;
    }

	public function getFirstName() {
		return $this->firstName;
    }

	public function getLastName() {
		return $this->lastName;
    }

	public function getUserName() {
		return $this->username;
    }

	public function getPassword() {
		return $this->password;
    }

    public function getAddress() {
    	return $this->strAddress;
    }

    public function getSuburb() {
        return $this->suburb;
    }

    public function getTown() {
    	return $this->town;
    }

	public function getBirthDay() {
		return $this->birthday;
    }

	public function getMobileNumber() {
		return $this->mobileNumber;
    }

	public function getGender() {
		return $this->gender;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getProfilePicture($rawForm=FALSE) {
      	if ($rawForm == TRUE) {
			return base64_encode($this->profilePicture);
      	} else {
        	return 'data:image/jpeg;base64,'. base64_encode($this->profilePicture);
      	}
	}

	/* Setter Functions */

	public function setIdentifier($id) {
		$this->identifier = $id;
	}

	public function setTitle($title) {
		$this->title = $title;
    }

	public function setFirstName($firstName) {
		$this->firstName = $firstName;
    }

	public function setLastName($lastNAme) {
		$this->lastName = $lastName;
	}

	public function setUserName($username) {
		$this->username = $username;
    }

	public function setPassword($password) {
		$this->password = $password;
    }

    public function setAddress($strAddress) {
      	$this->address = $strAddress;
	}

	public function setSuburb($suburb) {
		$this->suburb = $suburb;
	}

	public function setTown($town) {
		$this->town = $town;
	}

	public function setBirthDay($birthday) {
		$this->birthday = $birthday;
    }

	public function setMobileNum($mobileNumber) {
		$this->mobileNumber = $mobileNumber;
    }

	public function setGender($gender) {
		$this->gender = $gender;
    }

    public function setDescription($description) {
		$this->description = $description;
	}

    public function setProfilePicture($profilePicture) {
      	if ($profilePicture == NULL) {
        	$this->profilePicture = addslashes(file_get_contents("./assets/images/noProfilePicture.jpg"));
      	} else {
        	$this->profilePicture = $profilePicture;
      	}
    }

	public function __toString() {
		return "Title:" . $this->title .
		"\nFirst name: " . $this->firstName .
		"\nLast name: " . $this->lastName .
		"\nBirthday: " . $this->birthday .
		"\nMobile Number: " . $this->mobileNumber .
		"\nGender: " . $this->gender .
		"\nEmail(Username): " . $this->username .
		"\nPassword: " . $this->password .
      	"\nTown: " . $this->town .
      	"\nAddress: " . $this->address .
      	"\nProfile Picture: " . $this->profilePicture .
      	"\nDescription: " . $this->description;
	}
}
?>