<?php
require_once("user.class.php");

class Guest extends User {
    private $payInfoId;

    public function __construct($title, $firstName, $lastName, $username, $password, $strAddress, $suburb, $town, $birthday, $mobileNumber, $gender, $description, $profilePicture, $payInfoId) {
        parent::__construct($title, $firstName, $lastName, $username, $password, $strAddress, $suburb, $town, $birthday, $mobileNumber, $gender, $description, $profilePicture);
        $this->payInfoId = $payInfoId;
    }

    public function getPayInfoId() {
        return $this->payInfoId;
    }

    public function setPayInfoId($payInfoId) {
        $this->payInfoId = $payInfoId;
    }

    public function __toString() {
      	return "User: Guest\n" .
          parent::__toString() .
          "\nPayment Info ID: " . $this->payInfoId;
    }
}
?>
