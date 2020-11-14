<?php
require_once("database.class.php");
/*
 * Outputs top part of every html block.
 */
function outputHeadContent($title) {
  $head = "<!DOCTYPE html>";
  $head .= "<html lang=\"en\">";
  $head .= "<head>";
  $head .= "<title>".$title."</title>";
  $head .= "<meta charset=\"utf-8\">";
  $head .= "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no\">";
  $head .= "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">";
  $head .= "<meta name=\"HandheldFriendly\" content=\"true\">";
  $head .= "<link rel=\"shortcut icon\" type=\"image/ico\" href=\"./assets/images/favicon.ico\">";

  echo $head;
}

/*
 * Outputs nav bar.
 * Use:
 * "home" for any home page;
 * "us" for about us;
 * "in" for signin page;
 * "out" for signout page;
 * "guestreg" for guest register page;
 * "hostreg" for host register page;
 * "profile" for any profile page.
 */
function outputNav($page, $login) {
  $nav = "<nav id = \"myNav\" class=\"topbar\">";
  $nav .= "<ul>";

  //Link to home page
  if ($page != "home") {
    if ($login == GUEST || $login == HOST || $login == FALSE) {
      $nav .= "<li><a href=\"./index.php\">Home</a></li>";

    } else if ($login == ADMIN) {
      $nav .= "<li><a href=\"./admin_front_page.php\">Home</a></li>";
    }
  }

  //Link to about us page
  if ($page != "us") {
    $nav .= "<li><a href=\"./about_us.php\">About Us</a></li>";
  }

  //Link to profile page
  if ($page != "profile" && $login != FALSE) {
    if ($login == HOST) {
        $nav .= "<li><a href=\"./profile.php\">";
        $nav .= $_SESSION['user'];
        $nav .= "</a></li>";
    } else if ($login == GUEST) {
        $nav .= "<li><a href=\"./profile.php\">";
        $nav .= $_SESSION['user'];
        $nav .= "</a></li>";
    }
  }

  //Link to register page
  if (($login == FALSE || $login == HOST) && ($page != "guestreg")) {
    $nav .= "<li><a href=\"./guest_register.php\">Register as Guest</a></li>";
  }
  if (($login == FALSE || $login == GUEST) && ($page != "hostreg")) {
    $nav .= "<li><a href=\"./host_register.php\">Register as Host</a></li>";
  }

  //link to signin/logout page
  if ($login == FALSE) {
    $nav .= "<li><a href=\"./login.php\">Sign In</a></li>";
  } else {
    $nav .= "<li><a href=\"./logout.php\" onclick=\"return confirm('Are you sure you want to log out?');\">Sign Out</a></li>";
  }
  $nav .= "</ul></nav>";

  //add drop down navbar
  $nav .= "<nav class=\"dropdown\">";
  $nav .= "<button class=\"dropdown_button\"><img src=\"./assets/images/dropdown.png\"></button>";
  $nav .= "<div class=\"dropdown_content\">";
  //Link to home page
  if ($page != "home") {
    if ($login == GUEST || $login == FALSE || $login == HOST) {
      $nav .= "<li><a href=\"./index.php\">Home</a></li>";

    } else if ($login == ADMIN) {
      $nav .= "<li><a href=\"./admin_front_page.php\">Home</a></li>";
    }
  }

  //Link to about us page
  if ($page != "us") {
    $nav .= "<li><a href=\"./about_us.php\">About Us</a></li>";
  }

  //Link to profile page
  if ($page != "profile" && $login != FALSE) {
    if ($login == HOST) {
        $nav .= "<li><a href=\"./profile.php\">";
        $nav .= $_SESSION['user'];
        $nav .= "</a></li>";
    } else if ($login == GUEST) {
        $nav .= "<li><a href=\"./profile.php\">";
        $nav .= $_SESSION['user'];
        $nav .= "</a></li>";
    }
  }

  //Link to register page
  if (($login == FALSE || $login == HOST) && ($page != "guestreg")) {
    $nav .= "<li><a href=\"./guest_register.php\">Register as Guest</a></li>";
  }
  if (($login == FALSE || $login == GUEST) && ($page != "hostreg")) {
    $nav .= "<li><a href=\"./host_register.php\">Register as Host</a></li>";
  }

  //link to signin/logout page
  if ($login == FALSE) {
    $nav .= "<li><a href=\"./login.php\">Sign In</a></li>";
  } else {
    $nav .= "<li><a href=\"./logout.php\" onclick=\"return confirm('Are you sure you want to log out?');\">Sign Out</a></li>";
  }
  $nav .= "</ul></div></nav>";

  echo $nav;
}
//width:100%;background-color:rgb(204, 77, 77);text-align:center;padding:0;margin-top:5%;margin-bottom:0;margin-right:0;margin-left:0;
/*
 * Outputs a footer for a webpage with the current year called from the system.
 */
function outputFooter() { ?>
  <div class="footer">
    	<p> &copy; <?php echo date("Y")?> Disa Travels. All rights reserved. T&amp;Cs apply. Created by Dead Coders Society. </p>
  </div>
  <?php
}

?>
