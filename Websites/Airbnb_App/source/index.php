
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*-------------------------------------------------*/
  require_once("database.class.php") ;
  require_once("authenticate.php");
  require_once("functions.php");

  $loggedIn = isLoggedIn();
  $db = new Database();
  $db->connect();

  outputHeadContent('Disa Travels - Home');
 ?>

    <link type = "text/css" rel = "stylesheet" href = "./assets/style/desktop.css" media= "screen and (min-width:1170px)"/>
    <link type = "text/css" rel = "stylesheet" href = "./assets/style/mobile.css"  media = "screen and (max-width:1169px)"/>
    <link type = "text/css" rel = "stylesheet" href = "./assets/style/home_nav.css">

  </head>

  <body>
    <div class = "slider">

        <figure>
          <img src = "./assets/images/river.jpg">
          <img src = "./assets/images/slide2.jpg">
          <img src = "./assets/images/elephant.jpg">
          <img src = "./assets/images/feeding.jpg">
        </figure>

        <?php outputNav("home", $loggedIn); ?>

        <div class="search">
          <h3> Start searching for your Accomodation</h3>
        </div>
      </div>

      <div class = "travel">
        <h1>Travel the Western Cape</h1>
        <a href="./search_main.php"><button>Search</button></a>
      </div>

      <?php /* display host's listings */
        if ($loggedIn == HOST) {

        if (isset($_SESSION['user'])) {

        ?>
      <div class = "row">
        <div class = "col">
        <div class = "listings" >
          <hr>
          <h3>Currently listed units</h3>
          <ul>

          <?php foreach($db->get_users_listings($_SESSION['user']) as $unit) {
              echo "<li><form action=\"./edit_unit.php\" method=\"POST\">";
              echo "<button type=\"submit\" name=\"unit\" value=\"";
              echo $unit->getUniqueIdentifier()."\">";
              echo $unit->getAccommodationName()."</button></form></li>";
            }
          ?>
          <br>
          <form action ="./add_unit.php" method = "get"><button name="unit" type="submit" value = "1" >Add Listing</button></form>
          <h3>Overview Statistics</h3>
          <ul>
            <p> <?php echo $db->get_num_bookings();?> Bookings</p>
          </ul>

          <hr>
          </ul>
        </div>
        </div>
      </div>
      <?php  }
      }?>

    <div class="container">

      <div class="row">
        <div class = "heading">

          <h1>Amazing Offers</h1>
        </div>
        <div class="col">
          <?php $stellenbosch = $db->get_listing(1); ?>
          <h2><?php echo $stellenbosch->getAccommodationName(); ?></h2>
          <img src="<?php echo $stellenbosch->getMainPicture(); ?>" alt="hotel">

          <button class = "view" href = "#myModal">View Accommodation</button>
            <div id = "myModal" class  = "modal">
              <div class = "modal-stuff">
                <span class="close">&times;</span>
                  <p><strong><?php echo $stellenbosch->getExtraPricingsDiscount(); ?></strong></P>
                  <p>R<?php echo $stellenbosch->getTarrif(); ?> p/p per night</p>
                  <p><em><?php echo $stellenbosch->getDescription(); ?></em></p>

                <form action ="./Individual_unit_page.php" method = "get"><button name="unit" type="submit" value = "1" >More Info</button></form>
              </div>
            </div>
        </div>

        <div class="col">
          <?php $knysna = $db->get_listing(2); ?>
          <h2><?php echo $knysna->getAccommodationName(); ?></h2>
          <img src="<?php echo $knysna->getMainPicture(); ?>" alt="hotel">

        <button class = "view" href = "#myModal1">View Accommodation</button>
          <div id = "myModal1"class = "modal">
            <div class = "modal-stuff">
              <span class= "close">&times;</span>
                <p><strong><?php echo $knysna->getExtraPricingsDiscount(); ?></strong></P>
                <p>R<?php echo $knysna->getTarrif();?> p/p per night</p>
                <p><em><?php echo $knysna->getDescription(); ?></em></p>

              <form action ="./Individual_unit_page.php" method = "get"><button name = "unit" type = "submit" value = "2" >More Info</button></form>
            </div>
          </div>
        </div>

        <div class="col">
          <?php $palmtree = $db->get_listing(30); ?>
          <h2><?php echo $palmtree->getAccommodationName(); ?></h2>
          <img src="<?php echo $palmtree->getMainPicture(); ?>" alt="hotel">

          <button class = "view"href = "#myModal2">View Accommodation</button>
            <div id = "myModal2"class = "modal">
              <div class = "modal-stuff">
                <span class="close">&times;</span>
                  <p><strong><?php echo $palmtree->getExtraPricingsDiscount(); ?></strong></P>
                  <p>R<?php echo $palmtree->getTarrif();?> p/p per night</p>
                  <p><em><?php echo $palmtree->getDescription(); $db->disconnect();?></em></p>

                <form action ="./Individual_unit_page.php" method = "get"><button name = "unit" type = "submit" value = "30" >More Info</button></form>
              </div>
            </div>
          </div>
    </div>

    <div class ="heading2">
      <h1>Popular Destinations</h1>
    </div>

    <div class = "container">
      <div class="row">
        <div class="col">
          <?php $sunstays = $db->get_listing(16); ?>
          <h2><?php echo $sunstays->getAccommodationName(); ?> </h2>
          <img src="<?php echo $sunstays->getMainPicture(); ?>" alt="hotel">
          <button class = "view"href = "#myModal3">View Accommodation</button>
          <div id = "myModal3"class = "modal">
            <div class = "modal-stuff">
              <span class="close">&times;</span>

                <p>R<?php echo $sunstays->getTarrif();?> p/p per night</p>
                <p><em><?php echo $sunstays->getDescription(); $db->disconnect();?></em></p>

              <form action ="./Individual_unit_page.php" method = "get"><button name = "unit" type = "submit" value = "16" >More Info</button></form>
            </div>
          </div>
        </div>

        <div class = "col">
          <?php $pinelodge = $db->get_listing(17); ?>
          <h2><?php echo $pinelodge->getAccommodationName(); ?> </h2>
          <img src="<?php echo $pinelodge->getMainPicture(); ?>" alt="hotel">
          <button class = "view"href = "#myModal4">View Accommodation</button>
          <div id = "myModal4"class = "modal">
            <div class = "modal-stuff">
              <span class="close">&times;</span>

                <p>R<?php echo $pinelodge->getTarrif();?> p/p per night</p>
                <p><em><?php echo $pinelodge->getDescription(); $db->disconnect();?></em></p>

              <form action ="./Individual_unit_page.php" method = "get"><button name = "unit" type = "submit" value = "17" >More Info</button></form>
          </div>
        </div>
      </div>

        <div class="col">
          <?php $laguna = $db->get_listing(31); ?>
          <h2><?php echo $laguna->getAccommodationName(); ?> </h2>
          <img src="<?php echo $laguna->getMainPicture(); ?>" alt="hotel">
          <button class = "view"href = "#myModal5">View Accommodation</button>
          <div id = "myModal5"class = "modal">
            <div class = "modal-stuff">
              <span class="close">&times;</span>

                <p>R<?php echo $laguna->getTarrif();?> p/p per night</p>
                <p><em><?php echo $laguna->getDescription(); $db->disconnect();?></em></p>

              <form action ="./Individual_unit_page.php" method = "get"><button name = "unit" type = "submit" value = "31" >More Info</button></form>
          </div>
        </div>
        </div>
      </div>
    </div>

      <div class = "heading4">
        <h1>Weekend Getaway</h1>
      </div>
  <div class = "container">
      <div class="row">
        <div  class="col">
          <?php $chandelier = $db->get_listing(19); ?>
          <h2><?php echo $chandelier->getAccommodationName(); ?></h2>
          <img src="<?php echo $chandelier->getMainPicture(); ?>" alt="hotel">
          <button class = "view"href = "#myModal6">View Accommodation</button>
          <div id = "myModal6"class = "modal">
            <div class = "modal-stuff">
              <span class="close">&times;</span>
              <p>
                <li>R<?php echo $chandelier->getTarrif();?> p/p per night</li>
                <li><?php echo $chandelier->getDescription(); $db->disconnect();?></li>
              </p>
              <form action ="./Individual_unit_page.php" method = "get"><button name = "unit" type = "submit" value = "19" >More Info</button></form>
          </div>
        </div>
        </div>

        <div  class="col">
          <?php $lake = $db->get_listing(20); ?>
          <h2><?php echo $lake->getAccommodationName(); ?></h2>
          <img src="<?php echo $lake->getMainPicture(); ?>" alt="hotel">
          <button class = "view"href = "#myModal7">View Accommodation</button>
          <div id = "myModal7"class = "modal">
            <div class = "modal-stuff">
              <span class="close">&times;</span>
              <p>
                <li>R<?php echo $lake->getTarrif();?> p/p per night</li>
                <li><?php echo $lake->getDescription(); $db->disconnect();?></li>
              </p>
              <form action ="./Individual_unit_page.php" method = "get"><button name = "unit" type = "submit" value = "20" >More Info</button></form>
          </div>
        </div>
      </div>

        <div  class="col">
          <?php $tanagra = $db->get_listing(21); ?>
          <h2><?php echo $tanagra->getAccommodationName(); ?></h2>
          <img src="<?php echo $tanagra->getMainPicture(); ?>" alt="hotel">
          <button class = "view"href = "#myModal8">View Accommodation</button>
          <div id = "myModal8"class = "modal">
            <div class = "modal-stuff">
              <span class="close">&times;</span>
              <p>
                <li>R<?php echo $tanagra->getTarrif();?> p/p per night</li>
                <li><?php echo $tanagra->getDescription(); $db->disconnect();?></li>
              </p>
              <form action ="./Individual_unit_page.php" method = "get"><button name = "unit" type = "submit" value = "21" >More Info</button></form>
            </div>
          </div>
        </div>
      </div>
    </div>

 <div class ="container2">
    <div class= "heading3">
      <h1>Things to do in the Western Cape</h1>

    </div>
  </div>
  <div class ="heading5">
      <h2>Scenic walking Areas</h2>
  </div>

    <div class="row1">
      <div class="col1-2">


          <h3>Cape of Good Hope</h3>
          <p>It was first sighted by the Portuguese navigator Bartolomeu Dias in
          1488 on his return voyage to Portugal after ascertaining the southern
          limits of the African continent. One historical account says that Dias
          named it Cape of Storms and that John II of Portugal renamed it Cape of
          Good Hope (because its discovery was a good omen that India could be
          reached by sea from Europe); other sources attribute its present name to
          Dias himself. Known for the stormy weather and rough seas encountered
          there, the cape is situated at the convergence of the warm
          Mozambique-Agulhas current from the Indian Ocean and the cool Benguela
          current from Antarctic waters. Grass and low shrub vegetation is
          characteristic of the promontory, which is part of the Cape of Good Hope
          Nature Reserve (established 1939) that encompasses the southern tip of
          the peninsula. There is a lighthouse on Cape Point about 1.2 miles (2 km)
          east of the Cape of Good Hope.</p>
          <button>Learn more</button>
        </div>

        <div class = "col1-2">
          <img src = "/assets/images/cliff.jpg">
        </div>
      </div>
      <div class = "row1">
        <div class = "col1-2">
          <h3>Kalk Bay Harbour</h3>
          <p>Kalk Bay harbour is a working small fishing harbour. It is a great spot
          to explore; with a mix of local and tourist appeal. A small beach on one
          side of the harbour is great for those looking for a very gentle swim
          (life guards at peak times). A few restaurants from upmarket to local fish
          and chips; fresh fish for sale from the catch of the day and a few seals for
          entertainment.</p>
          <button>Learn more</button>
        </div>
        <div class = "col1-2">
          <img src = "/assets/images/harbour.jpg">
        </div>
      </div>

      <div class ="heading5">
          <h2>Historical Sites</h2>
      </div>

      <div class = "row1">
        <div class = "col1-2">
          <h3>Groot Constantia</h3>
          <p>Groot Constantia is South Africa's oldest wine producing estate, (est: 1685).
          Situated 20 minutes from the Cape Town CBD and one of Cape Town's Big 7 tourist
          attractions, Groot Constantia is a must visit for the whole family. With two
          outstanding restaurants, Jonkershuis and Simon's, wine tastings, chocolate and wine
          pairing, cellar tours and a museum that reminds one of a bygone era, this beautiful
          historic winery is a popular attraction that exudes natural beauty. Groot Constantia
          can also be reached hoping on City Sightseeing's Wine Bus.</p>

        <button>Learn More</button>
        </div>
        <div class = "col1-2">
          <img src = "/assets/images/trees.jpg">
        </div>


      </div>


        <div class = "row1">
          <div class = "col1-2">
            <h3>Robben Island Museum</h3>
            <p>Robben Island Museum is an island in Table Bay, 6.9 kilometres (4.3 mi) west
              of the coast of Bloubergstrand, Cape Town, South Africa. The name is Dutch for
              "seal island." Robben Island is roughly oval in shape, 3.3 km (2.1 mi) long
              north-south, and 1.9 km (1.2 mi) wide, with an area of 5.08 km2 (1.96 sq mi).
              [2] It is flat and only a few metres above sea level, as a result of an ancient
              erosion event. Nobel Laureate and former President of South Africa Nelson Mandela
              was imprisoned there for 18 of the 27 years he served behind bars before the fall
              of apartheid. To date, three former inmates of Robben Island have gone on to become
              President of South Africa: Nelson Mandela, Kgalema Motlanthe,[3] and Jacob Zuma.</p>
              <button>Learn More</button>
          </div>
          <div class = "col1-2">
            <img src = "/assets/images/island.jpg">
          </div>
      </div>
      <div class ="heading6">
          <h1>Travel Inspiration</h1>
      </div>
    <div class = "row2">
      <div class = "col1-3">
        <img src = "/assets/images/adventure.jpg">


      </div>
      <div class = "col1-3">
        <img src = "/assets/images/climbing.jpg">


      </div>
      <div class = "col1-3">
        <img src = "/assets/images/forest.jpg">

      </div>
    </div>
    <script src = "./assets/scripts/functions.js" ></script>
    <script src = "./assets/scripts/navscroll.js" ></script>
    <script src = "./assets/scripts/popup.js" ></script>
  </body>
</html>
