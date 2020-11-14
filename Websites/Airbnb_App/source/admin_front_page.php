<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*-------------------------------------------------*/
require_once('database.class.php');
require_once("authenticate.php");
require_once("functions.php");

$loggedIn = isLoggedIn();
$db = new Database();
$db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $db->backup();
}

outputHeadContent('Home-Admin');
?>
    <link type = "text/css" rel = "stylesheet" href = "./assets/style/adminFrontDesk.css" media= "screen and (min-width:1170px)"/>
    <link type = "text/css" rel = "stylesheet" href = "./assets/style/adminFrontmobile.css"  media = "screen and (max-width:1169px)"/>
  </head>

  <body>
    <?php
    $numH = $db->get_num_hosts();
    $numL = $db->get_num_listings();
    $numG = $db->get_num_guests();
    $numB = $db->get_num_bookings();

    $db->disconnect();
    ?>

  <div class ="sidebar">
    <section>
      <nav class="dropdown">
        <button><img src="./assets/images/dropdown.png"></button>
          <div class="dropdown_content">
            <a href="./index.php">Home</a>
            <a href="./about_us.php">About Us</a>
            <a href="./profile.php"><?php echo $_SESSION['user']; ?></a>
            <a href="./host_register.php">Register as Host</a>
            <a href="./guest_register.php">Register as Guest</a>
            <a href="./logout.php" onclick="return confirm('
              Are you sure you want to log out?')">Sign Out</a>
          </div>
        </nav>
      </div>
    </section>

  <div class = "sidebar-right">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
      <input type="submit" name="backup" value="Backup Database">
    </form>

    <input type="submit" name="initialize" value="Initialize Database">


    <h1>Graphs</h1>
    <div class = "col2">
      <h3>Guests</h3>

      <div class ="skill">
        <div class = "skill-level"></div>
      </div>
      <h3>Hosts</h3>

      <div class ="skill">
        <div class = "skill-level"></div>
      </div>
      <h3>Listings</h3>

      <div class ="skill">
        <div class = "skill-level"></div>
      </div>
      <h3>Bookings</h3>

      <div class ="skill">
        <div class = "skill-level"></div>
      </div>
      <h3>Visits</h3>

      <div class ="skill">
        <div class = "skill-level"></div>
      </div>
      <h3> Reviews </h3>

      <div class ="skill">
        <div class = "skill-level"></div>
      </div>
    </div>
   </div>

    <div class = "sidesection">

      <div class = "heading">
        <h1>Current Statistics</h1>
      </div>
    <div class = "row">

      <div class = "col">
        <p> <?php echo "$numG" ?>  Guests</p>

        <form action ="./list.php" method = "get"><button name = "list" type = "submit" value = "guests" >List of Guests</button></form>
        </div>
      <div class = "col">
        <p> <?php echo "$numH" ?>Hosts</p>
         <form action ="./list.php" method = "get"><button name = "list" type = "submit" value = "hosts">List of Hosts</button></form>
        </div>
      <div class = "col">
        <p> <?php echo "$numL"?> Listings</p>
        <form action ="./list.php" method = "get"><button name = "list" type = "submit" value = "listings">List of Units</button></form>
        </div>

      <div class = "row">
        <div class = "col">
          <p> <?php echo "$numB"?> Bookings</p>
          <form action ="./list.php" method = "get"><button name = "subject" type = "submit" value = "Bookings">List of Bookings</button></form>
          </div>
        <div class = "col">

          <p> 0 Visits </p>
          <form action ="./list.php" method = "get"><button>View Visits</button></form>
          </div>
          <div class = "col">
            <p> 3 Reviews </p>
            <form action ="./list.php" method = "get"><button>View Reviews</button></form>
        </div>
      </div>
    </div>

    <script scr="./assets/scripts/functions.js"></script>
  </body>
</html>
