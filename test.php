<?php
  session_start();
//   include 'navbar.php';
  require 'model/db.php';
  require 'select_locker.php';
?>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="select_locker.js"></script>
</head>

<form role="form" method="post" action="">
    <div class="row">
        <div class="form-group">
            <label for="sizes">Size</label>
            <select id="sizes" name="sizes">
                <option selected="" disabled="">Select Size </option>
                <?php
                require 'select_locker.php';
                $sql2 = "SELECT distinct locker_size from `locker` WHERE `locker_status`='Available' AND `locker_location`='Wallberg (Basement)' ORDER BY locker_size desc";
                $result2 = mysqli_query($conn, $sql2);
                
                while($size = mysqli_fetch_array($result2)) {
                    echo "<option value = '".$size['locker_size']."'>" .$size['locker_size']. "</option>";
                }
            ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="lockers">Locker No.</label>
            <select class="form-control" id="lockers" name="lockers">
                
            </select>
        </div>
    </div>
</form>