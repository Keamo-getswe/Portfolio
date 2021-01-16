<?php
class Administrator {

	private $identifier;
	private $title;
	private $firstName;
	private $lastName;
	private $username;
	private $password;
	private $mobileNumber;

    public function __construct($title, $firstName, $lastName, $username, $password, $mobileNumber) {
		$this->identifier = -1;
		$this->title = $title;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->username = $username;
		$this->password = $password;
		$this->mobileNumber = $mobileNumber;
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

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getMobileNumber() {
		return $this->mobileNumber;
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

	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function setMobileNumber($mobileNumber) {
		$this->mobileNumber = $mobileNumber;
	}

	public function __toString() {
		return "Title: " . $this->title .
		"\nFirst Name: " . $this->firstName .
		"\nLast Name: " . $this->lastName .
		"\nUsername: " . $this->username .
		"\nMobile Number: " . $this->mobileNumber;
	}
}
?>