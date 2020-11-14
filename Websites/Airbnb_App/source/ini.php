<?php
/*
 * Connect to the disas database.
 */
 function connect() {
  $dbhost = "localhost";
  $dbuser = "admin";
  $dbpass = "8eGE3bMR3fiy";
  $db = "disas";

  $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
  if ($conn->connection_error) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
  }
  return $conn;
}

/*
 * Disconnect from the disas database.
 */
function disconnect($conn) {
  $conn->close();
}
?>
