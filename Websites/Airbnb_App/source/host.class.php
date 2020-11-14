<?php

require_once("user.class.php");
require_once("listing.class.php");

class Host extends User {

  public function __contruct($tow, $addr, $ti, $firstName, $lastName, $birthDay, $mobileNum, $gen, $user_name, $pass, $profilePicture, $descr) {
    parent::__construct($tow, $addr, $ti, $firstName, $lastName, $birthDay, $mobileNum, $gen, $user_name, $pass, $profilePicture, $descr);

  }

  public function __toString() {
    return "User: Host\n" .
    parent::__toString();
  }
}
?>
