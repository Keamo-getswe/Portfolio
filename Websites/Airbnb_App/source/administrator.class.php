<?php
require_once("user.class.php");

class Administrator extends User {

  //public static $administrator_count = 0;

  public function __contruct($tow, $addr, $ti, $firstName, $lastName, $birthDay, $mobileNum, $gen, $user_name, $pass, $profilePicture, $descr) {
    parent::__construct($tow, $addr, $ti, $firstName, $lastName, $birthDay, $mobileNum, $gen, $user_name, $pass, $profilePicture, $descr);

    //self::$administrator_count++;
  }

  /* Returns the number of administrator created
  public static function getAdministatorsTotal() {
    return self::$administrator_count;
  }*/
}
?>
