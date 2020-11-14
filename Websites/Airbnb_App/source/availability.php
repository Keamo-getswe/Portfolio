<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*-------------------------------------------------*/
  require_once("database.class.php");
  session_start();

  $db = new Database();
  $db->connect();

  $arrive = $_GET['arrival'];
  $depart = $_GET['depart'];
  $unit_id = $_SESSION['unit'];

  $ok = $db->isAvailable($unit_id, $arrive, $depart);

  if ($ok) {
    echo "<br><br><p><em>Yay! This listing is available.</em></p>";
  } else {
    echo "<br><br><p><em>Oh no... this listing is already booked for those days</em></p>";
  }
  $db->disconnect();
 ?>
