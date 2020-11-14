<?php
/*
 * Author: Ibrahim Sheriff Sururu
 *
 * This handles the production of the company's about us page.
 */

require_once("authenticate.php");
require_once("functions.php");

$log_status = isLoggedIn();

outputHeadContent("About Us");
  ?>
  <link type="text/css" rel="stylesheet" href="./assets/style/about_us1.css" media="screen and (min-width:1170px)"/>
  <link type="text/css" rel="stylesheet" href="./assets/style/about_us2.css" media="screen and (max-width:1169px)"/>
</head>
<body>
  <div class="video_container">
    <?php
    outputNav("About Us", $log_status);
    ?>

    <video loop muted autoplay>
      <source src="./assets/videos/Surfer.mp4" type="video/mp4">
        Your brower does not support the video tag
      </video>

  <div class="video_overlay">
    <p>
    Get to Know Us.
    </p>
  </div>
  </div>
  <div class="content">
  <div class="our_story">
    <h1>Disa Travels</h1>
    <h2>Our Story</h2>
    <p>
      Cheesy as it may sound, the Disa Travels team met at university,but not
      just any university, Stellenbosch University!
      Brought together by a Computer Science 334 project the group formed to
      create a interesting Dead Code Society which then gave birth to
      disatravels.com a travel website that lets people from Western Cape host
      their accommodation and gives travellers access to that listed accommodation.
      <br>
      The team decided to display the beauty of the Western Cape focusing on
      the behemoth province to ensure that travellers were getting the best
      deals in the Western Cape as the province did not have a wesbite servicing its
      hospitality needs to the fullest as Airbnb and Safarinow failed to deliver on
      focus, customer service and 'the real experiences', experiences that you can
      only find if you visit the same places a million times.
      <br>
      Applying state of the art placement algorithms, high tech quality control systems
      and love for the Western Cape this company is here to serve the people, bringing
      together awesome hosts and travellers to make memories.
    </p>
  </div>
  <div class="our_team">
  <h2>Our Team</h2>
  <p>
    Also known as the Dead Coders Society the Disa Travels team is a classy
    group of programmers looking to bring you the best user
    experience on the planet. Putting together the lastest technology, these
    guys will make you feel like just touring the website
    instead of the world!
  </p>
  <div class="robbie_container">
  <img src="./assets/images/robbie.jpg" alt="Robbie" />
  <div class="robbie_overlay">
  <p>
      A former venture capitalist, Robbie found a passion for code in his
      early 20s and has not stopped hacking at it since. He brings his business
      building experience to the team. He is also a part-time skydiving instructor.
  </p>
</div>
</div>

  <div class="elwinjacobie_container">

  <img src="./assets/images/elwin_and_jacobie.jpeg" alt="Elwin and Jacobie" />

<div class="elwinjacobie_overlay1">
  <p>
    Jacobie studies Computer Science with Stellenbosch University and has brought
    great structural and innovative elements to the website. With a passion for
    horse riding, she will find you the best places to ride in the Western Cape.
  </p>
</div>
   <div class="elwinjacobie_overlay2">
     <p>
      Elwin has a background in geo-informatics and brings this to the team
      with fresh takes on placement and mapping. He understands the landscape
      extremely well. He is a former professional scuba-diving coach.
  </p>
</div>
    </div>

<div class="ibra_container">
  <img src="./assets/images/ibra.jpg" alt="Ibra" />
  <div class="ibra_overlay">
  <p>
      A former professional soccer player, now retired due to a leg injury.
      Ibra a.k.a Zlatan brings the energy to the team (and maybe way too much soccer).
      His love for C and all things Java provide the team with a heavy systems
      programmer who loves creating good products for users.
  </p>
</div>
</div>

<div class="dionne_container">
<img src="./assets/images/dionne.jpg" alt="Dionne" />
<div class="dionne_overlay">
<p>
    Dionne has worked in web development for years and if you want something done,
    just ask her. She has developed websites for social media platforms, healthcare,
    business and education just to name a few. Her hobbies include singing and being
    an Instagram influencer.
</p>
</div>
</div>

</div>
<div class="our_mission">
  <p class="mission_paragraphs">
    <span class="mission_titles">Our Mission</span><br>
      Disa Travels is here to bring you the best accomodation in Western Cape, as simple as that! We believe in making
      sure to deliver on this one thing.
  </p>
  <p class="mission_paragraphs">
    <span class="mission_titles">Our Promise</span><br>
    We will find you the best accommodation in the Western Cape to ensure that your visit to the
    W.C is a memorable one. We are tired of stories that involve accomodation with broken sinks, toilets
    and beds with blackholes in them! Our website focuses on areas known for a good time.
  </p>
  <p class="mission_paragraphs">
    <span class="mission_titles">Our Essence</span><br>
    We are about Comfort, Ease and Enjoyment.
  </p>
  <p class="mission_paragraphs">
    <span class="mission_titles">Our Vibe</span><br>
    Like the beautiful Disa flower, we are all about beautiful memorable days.
  </p>
</div>
  <div class="contact_us">
  <h2>Contact Us</h2>
  <p>
    Narga B<br>
    Ryneveld Street<br>
    Stellenbosch Central<br>
    Stellenbosch<br>
    7599<br>
    Phone Number: +27 (0) 74789 0586<br>
    Email: help@disatravels.com<br>
    <br>
    <strong>
    If you have any issues with this website, please contact our
    administrator:
  </strong>
    <br>
    Prof J. Geldenhuys<br>
    Email: admin@disatravels.com
  </p>
</div>
</div>
<div class="footer">
  	<p> &copy; <?php echo date("Y")?> Disa Travels. All rights reserved. T&amp;Cs apply. Created by Dead Coders Society. </p>
</div>
    <script src = "./assets/scripts/navscroll.js" ></script>
  </body>
</html>
