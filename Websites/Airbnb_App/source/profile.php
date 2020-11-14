<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*-------------------------------------------------*/
	require_once("database.class.php");
	require_once("authenticate.php");
	require_once("functions.php");

	$login_status = isLoggedIn();

	$db = new Database();
	$db->connect();

	if ($login_status == GUEST) {
		$user = $db->get_guest($_SESSION['user']);
		outputHeadContent("Profile - Guest");

	} else if ($login_status == HOST)  {
		$user = $db->get_host($_SESSION['user']);
		outputHeadContent("Profile - Host");

	} else if ($login_status == ADMIN) {
		$user = $db->get_administrator($_SESSION['user']);
		outputHeadContent("Profile - Admin");
	}

	/* get all the information */
	$heading = $user->getTitle()." ".$user->getFirstName()." ".$user->getLastName();
	$profilePicture = $user->getProfilePicture();
	$description = $user->getDescription();

	$username = $user->getUserName();
	$address = $user->getAddress();
	$town = $user->getTown();
	$birthday = $user->getBirthday();
	$number = $user->getMobileNum();
	$id = $user->getUniqueIdentifier();

	$db->disconnect();
	?>

	<link rel="stylesheet" href="./assets/style/profile.css">
	</head>
	<body>
		<?php outputNav("profile", $login_status); ?>

		<div class="profilehead">
			<h1 class="heading1"><?php echo $heading; ?></h1>
			<img class="profilepicture" src="<?php echo $profilePicture?>" alt="profile pic">
		</div>

		<aside class="sidebar">
		<h2 class="heading2">Basic Info</h2>

		<div class="profileinfo">

			<table>
				<tr>
					<td><strong>Username (Email)</strong></td>
					<td><?php echo $username;?></td>
				</tr>
				<tr>
					<td><strong>Address</strong></td>
					<td><?php echo $address.", ".$town;?></td>
				</tr>
				<tr>
					<td><strong>Mobile Number</strong></td>
					<td><?php echo $number;?></td>
				</tr>
				<tr>
					<td><strong>Birthday</strong></td>
					<td><?php echo $birthday;?></td>
				</tr>
			</table>
			<br>

		</div>

		<a href="./edit_profile.php"><button>Edit info</button></a>
		</aside>

		<div class="container1">
			<h3 class="heading3">More about me</h3>
			<p><?php echo $description?></p>
		</div>

		<aside class="sidebar2">
			<br>
			<?php if ($login_status == GUEST) {
							$db->output_favourites($id);
							echo "<a href=\"./user_history.php\"><button>History</button></a>";
							$db->disconnect();
						} ?>
		</aside>
		<?php
		if ($login_status == HOST) {
			?>
			<hr>
			<h5>Reviews by Guests</h5>
			<?php
			$db->connect();
			 $reviews = $db->get_review_host($user->getUniqueIdentifier());
			 foreach ($reviews as $r) {
				 $guest_name = $db->getGuestNameAsString($r->getName());
				 $review = $r->getReview();
				 ?>
				 <p><em><?php echo "\"".$review."\""; ?></em><?php echo " - ".$guest_name; ?></p>
			 <?php }
			 $db->disconnect();
		 } ?>
		 <hr>
	</body>
</html>
