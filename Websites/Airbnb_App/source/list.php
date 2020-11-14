<?php

require_once("database.class.php");
require_once("authenticate.php");
require_once("functions.php");

$loggedIn = isLoggedIn();

$db = new Database();
$db->connect();
$_SESSION['admin'] = TRUE;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $list = $_SESSION["list"];
  if (isset($_POST['delete_host'])) {
    $host = $db->get_host($_POST['delete_host']);
    $db->remove_host($host);

  } else if (isset($_POST['delete_guest'])) {
    $guest = $db->get_guest($_POST['delete_guest']);
    $db->remove_guest($guest);

  } else if (isset($_POST['delete_listing'])) {
    $listing = $db->get_listing($_POST['delete_listing']);
    $db->remove_listing($listing);

  }

} else {
  if(isset($_GET["list"])) {
    $list = $_GET["list"];
    $_SESSION["list"] = $list;
  } else {
    $list = $_SESSION["list"];
  }

  if ($list == "guests") {
    $_SESSION['return_reg_guests'] = TRUE;
    $_SESSION['return_reg_listings'] = FALSE;
    $_SESSION['return_reg_hosts'] = FALSE;

  } else if ($list == "hosts") {
    $_SESSION['return_reg_hosts'] = TRUE;
    $_SESSION['return_reg_guests'] = FALSE;
    $_SESSION['return_reg_listings'] = FALSE;

  } else if ($list == "listings") {
    $_SESSION['return_reg_listings'] = TRUE;
    $_SESSION['return_reg_hosts'] = FALSE;
    $_SESSION['return_reg_guests'] = FALSE;
  }
}
$title = "Registered ".ucfirst($list);

outputHeadContent($title);
 ?>

 <link rel="stylesheet" href="./assets/style/list_reg_style.css">
  </head>

  <body>
    <?php outputNav("list", $loggedIn); ?>

    <h1><?php echo $title; ?></h1>
    <?php
    if ($list == "guests") {
      $db->table_of_guests();
    } else if ($list == "hosts") {
      $db->table_of_hosts();
    } else {
      $db->table_of_listings();
    }
    $db->disconnect();
    ?>

  </body>
  <?php ouputFooter(); ?>
</html>
