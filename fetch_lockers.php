<?php
  // Dynamically fetch lockers for select menu by building and size selected
  session_start();
  require 'model/db.php';

  $result = mysqli_query($conn, 
    "SELECT * FROM `locker` 
      WHERE `locker_size` = '" .$_POST['size']. "'
      AND `locker_location` = '" .$_POST['location']. "'
      AND `locker_status`='Available'
  ");

  $output = 'option value="">Select Locker</option>';

  if($result) {
    while($row = mysqli_fetch_array($result)) {
      $output .= '<option value="' .$row["locker_id"].'">'.$row["locker_id"].'</option>';
    }
  } else {
    $output = 'option value="">None avabilable</option>';
  }

  echo $output;
?>
