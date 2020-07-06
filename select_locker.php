<?php
//   session_start();
  require 'model/db.php';

  if(isset($_POST['size'])) {
    $sql2 = "SELECT locker_id from `locker` WHERE `locker_status`='Available' AND `locker_location`='Wallberg (Basement)' AND `locker_size` = '".$_POST['size']."'";
    $result2 = mysqli_query($conn, $sql2);
    $lockers = array();
    while($locker = mysqli_fetch_array($result2)) {
        $lockers[] = $locker;
    }
    echo json_encode($lockers);
  }
?>
