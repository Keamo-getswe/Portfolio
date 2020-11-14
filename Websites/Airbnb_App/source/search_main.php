<?php
require_once("authenticate.php");
require_once("functions.php");

$loggedIn = isLoggedIn();

outputHeadContent("Search");
 ?>

<link rel="stylesheet" href="./assets/style/search_main_style.css">
</head>

<body>
  <?php outputNav("search", $loggedIn); ?>

  <div class="container">
        <h1>Search for your perfect getaway</h1>
      </div>

      <div class="where">
        <h3>Where do you want to go?</h3>
        <form action="search_filter.php" method="POST">
        <div class="search_block">
          <input id="where_search" type="search" name="destination" placeholder="search...">
        </div>
      </form>
          <br>
          <br>

      <div class="row">
        <div class="column">
        <form action="search_filter.php" method="POST">
          <button type="submit" name="destination" value="Cape Town">Cape Town
        </button></form>
        <img src="./assets/images/cape_town.jpg" alt="Cape Town"><br>
        </div>


        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="destination" value="Stellenbosch">Stellenbosch
          </button></form>
          <img src="./assets/images/stellenbosch.jpg" alt="Stellenbosch"><br>
        </div>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="destination" value="Hermanus">Hermanus
            </button></form>
          <img src="./assets/images/hermanus.jpg" alt="Hermanus"><br>
        </div>
        </form>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="destination" value="Paarl">Paarl
          </button></form>
          <img src="./assets/images/paarl.jpg" alt="Paarl"><br>
        </div>
      </div>

      <div class="row">
        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="destination" value="Oudtshoorn">Oudtshoorn
          </button></form>
          <img src="./assets/images/oudtshoorn.jpg" alt="Oudtshoorn"><br>
        </div>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="destination" value="Knysna">Knysna
          </button></form>
          <img src="./assets/images/knysna.jpg" alt="Knysna"><br>
        </div>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="destination" value="Franschhoek">Franschhoek
          </button></form>
          <img src="./assets/images/franschhoek.jpg" alt="Franschhoek"><br>
        </div>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="destination" value="Mossel Bay">Mossel Bay
          </button></form>
          <img src="./assets/images/mossel_bay.jpg" alt="Mossel Bay"><br>
        </div>
      </div>
      </div>

      <div class="holiday">
        <h3>I don't care where, I just want to have fun</h3>
        <div class="row">
        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit"name="holiday" value="beach_type">Beach
          </button></form>
          <img src="./assets/images/beach.jpg" alt="Beach"><br>
        </div>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="holiday" value="outdoor_type">Outdoor
            </button></form>
          <img src="./assets/images/outdoor.jpg" alt="Outdoor"><br>
        </div>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="holiday" value="relax_type">Relax
          </button></form>
          <img src="./assets/images/relax.jpg" alt="Relax"><br>
        </div>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="holiday" value="good_food_type">Good Food
            </button></form>
          <img src="./assets/images/food.jpeg" alt="Good Food"><br>
        </div>
      </div>

      <div class="row">
        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="holiday" value="city_vibe_type">City Vibe
            </button></form>
          <img src="./assets/images/city_vibe.jpg" alt="City Vibe"><br>
        </div>

        <div class="column">
          <form action="search_filter.php" method="POST">
          <button type="submit" name="holiday" value="adventure_type">Adventure
          </button></form>
          <img src="./assets/images/adventure.jpg" alt="Adventure"><br>
        </div>
      </div>
      </div>

    <script src="./assets/scripts/functions.js"></script>
    <script src = "./assets/scripts/navscroll.js" ></script>
    </body>
  </html>
