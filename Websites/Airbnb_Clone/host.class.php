<?php
require_once("user.class.php");

class Host extends User {

    public function __construct($title, $firstName, $lastName, $username, $password, $strAddress, $suburb, $town, $birthday, $mobileNumber, $gender, $description, $profilePicture) {
        parent::__construct($title, $firstName, $lastName, $username, $password, $strAddress, $suburb, $town, $birthday, $mobileNumber, $gender, $description, $profilePicture);
    }

    public function __toString() {
        return "User: Host\n" .
        parent::__toString();
    }
}
?>
