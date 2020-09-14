<?php
  $serverName = "localhost";
  $userName = "root";
  $password = "root";
  $database = "lockers_lockers";

  // Create connection
  $conn = mysqli_connect($serverName, $userName, $password, $database);

  // Check Connection
  if ((!isset($DB_SETTINGS['DONT_CHECK_CONNECTION']) || !$DB_SETTINGS['DONT_CHECK_CONNECTION']) && !$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // echo "Connected successfully";
?>
