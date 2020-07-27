<?php
  // Dynamically fetch lockers for select menu by building and size selected
  session_start();
  require 'model/db.php';

  $sql = "SELECT * FROM `locker`
    WHERE `locker_size` = '" .mysqli_real_escape_string($conn,$_GET['size']). "'
    AND `locker_location` = '" .mysqli_real_escape_string($conn,$_GET['location']). "'";

  $result = mysqli_query($conn, $sql);

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
