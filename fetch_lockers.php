<?php
  /* Dynamically fetch lockers for select menu by building and size selected
   * Data returned in a JSON array
   * If an invalid value is selected or no locker is found, an empty JSON array is returned
   * Else, the available lockers are appended to a JSON array, after
   * "Select Locker"
   */

  session_start();
  require 'model/db.php';

  $sql = "SELECT * FROM `locker`
    WHERE `locker_size` = '" .mysqli_real_escape_string($conn,$_GET['size']). "'
    AND `locker_location` = '"
                      .mysqli_real_escape_string($conn,$_GET['location']). "'
    AND locker_status LIKE 'Available'";
    //echo($sql);

  $result = mysqli_query($conn, $sql);

  $output = "";
  $arr = array("Select Locker"); // This is a magic value.
                                 // Please see the select.locker change event
                                 // handler in footer.php

  if($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $arr[] = $row['locker_id'];
      $output = json_encode($arr);
    }
  } else {
    $output = '[]';
  }

  echo $output;
?>
