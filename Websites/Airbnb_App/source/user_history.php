	<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	/*-------------------------------------------------*/

		require_once("database.class.php");
		require_once("functions.php");
		require_once("authenticate.php");

		$db = new Database();
		$db->connect();

		$login_status = isLoggedIn();
		if ($login_status == GUEST) {
			$user = $db->get_guest($_SESSION['user']);
			outputHeadContent("Profile - Guest");
		}
		$db->disconnect();
	?>
	<link rel="stylesheet" href="./assets/style/user_history_style.css">
  </head>

  <body>
	<?php
		outputNav('profile', $login_status);
	?>
		<h1>User History</h1>
	<?php
		$db->history_table($_SESSION['user']);
	?>

  </body>
</html>
