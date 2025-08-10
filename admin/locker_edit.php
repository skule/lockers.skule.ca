<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Manage Lockers";
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  if (isset($_POST['submit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $sql = "UPDATE `locker` SET locker_id='$id', locker_status='$status', locker_price='$price', locker_location='$location' WHERE locker_id='$id'";
    if (mysqli_query($conn, $sql)) {
      $msg = "<a href='locker.php' class='white-text'><i class='fas fa-arrow-circle-left'></i></a> Update Successfull";
      $msgClass = "green";
    } else {
      $msg = "Update error: " . $sql . "<br>" . mysqli_error($conn);
      $msgClass = "red";
    }
  }

  // handle the get request base on user id
  if (isset($_REQUEST['id'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $sql = "SELECT * FROM `locker` WHERE `locker_id`='$id'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);
  }

?>
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5><i class="fas fa-edit"></i> Edit locker <span class="blue-text"><?php echo htmlspecialchars($row['locker_id']); ?></span></h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="?id=<?php echo htmlentities($_GET['id'], ENT_QUOTES); ?>" novalidate>
        <div class="row">
          <div class="input-field col s12">
            <input type="text" id="id" name="id" value="<?php echo htmlentities($row['locker_id'], ENT_QUOTES); ?>">
            <label for="id">Locker ID (must be unique)</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="location" type="text" name="location" value="<?php echo htmlentities($row['locker_location'], ENT_QUOTES); ?>">
            <label for="location">Location</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <select name="status" id='status'>
              <option value="<?php echo htmlentities($row['locker_status'], ENT_QUOTES); ?>" selected><?php echo $row['locker_status']; ?></option>
              <?php
                $thisOption = "Available";
                if($thisOption != $row['locker_status'])
                  echo("<option value='".htmlentities($thisOption, ENT_QUOTES)."'>".htmlspecialchars($thisOption)."</option>");
              ?>
              <?php
                $thisOption = "Booked";
                if($thisOption != $row['locker_status'])
                  echo("<option value='".htmlentities($thisOption, ENT_QUOTES)."'>".htmlspecialchars($thisOption)."</option>");
              ?>
              <?php
                $thisOption = "Damaged";
                if($thisOption != $row['locker_status'])
                  echo("<option value='".htmlentities($thisOption, ENT_QUOTES)."'>".htmlspecialchars($thisOption)."</option>");
              ?>
            </select>
            <label for='status'>Status</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="price" type="text" name="price" value="<?php echo $row['locker_price']; ?>">
            <label for="price">Price</label>
          </div>
        </div>
        <div class="row">
          <div class="center">
            <button type="submit" class="waves-effect waves-light btn blue" name="submit">Update</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</div>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
