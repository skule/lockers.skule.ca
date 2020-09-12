<?php

  session_start();
  include 'navbar.php';
  require_once 'model/db.php';

  $msg = $msgClass = '';
  function get_price($date1, $date2) {
    $diff = date_diff(date_create($date1), date_create($date2));
    $price = $diff->format("%a");
    return $price * 1;
  }

  // handle the get request base on user id
  if (isset($_REQUEST['id'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $sql = "SELECT * FROM `locker` WHERE `locker_id`='$id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)."<br/>\n$sql");

    $row = mysqli_fetch_array($result);

    $_SESSION['locker_id'] = $row['locker_id'];
    $_SESSION['locker_price'] = $row['locker_price'];
    $_SESSION['locker_location'] = $row['locker_location'];
  }

  // Process booked locker and insert into database
  if (filter_has_var(INPUT_POST, 'book')) {
    $start = mysqli_real_escape_string($conn, date("Y-m-d",strtotime($_POST['start'])));
    $end = mysqli_real_escape_string($conn, date("Y-m-d",strtotime($_POST['end'])));
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $semail = mysqli_real_escape_string($conn, $_SESSION['s_email']);
    $lid = mysqli_real_escape_string($conn, $_POST['lockerid']);

    if ($end <= $start) {
      $msg = "Please pick a correct date";
      $msgClass = "red";
    } else {
      $totalPrice = get_price($start, $end);

      $sql = "INSERT INTO `record` (record_start, record_end, record_price, student_email, locker_id)
      VALUES ('$start', '$end', '$totalPrice', '$semail', '$lid');";
      $sql .= "UPDATE `locker` SET locker_status='Booked' WHERE locker_id='$lid'";

      $result = mysqli_multi_query($conn, $sql) or die(mysqli_error($conn)."<br/>\n$sql");
      $msg = "<a href='index.php' class='white-text'><i class='fas fa-arrow-circle-left'></i></a> Booking success. Pending admin approval.";
      $msgClass = "green";
    }
  }
  mysqli_close($conn);
?>

<!-- TODO: Add locker location to records -->
<section class="section">
    <div class="container">
        <h5><i class="fab fa-wpforms"></i> Booking information</h5>
        <div class="divider"></div><br>
        <?php if($msg != ''): ?>
        <div class="card-panel <?php echo $msgClass; ?>">
            <span class="white-text"><?php echo $msg; ?></span>
        </div>
        <?php endif ?>
        <form enctype="multipart/form-data" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            class="card-panel">
            <div class="row">
                <div class="input-field col s6 m6">
                    <input readonly type="text" id="lockerlocation" name="lockerlocation"
                        value="<?php echo $_SESSION['locker_location']; ?>">
                    <label for="id">Building</label>
                </div>

                <div class="input-field col s6 m6">
                    <input readonly type="text" id="lockerid" name="lockerid"
                        value="<?php echo $_SESSION['locker_id']; ?>">
                    <label for="id">Locker No</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6">
                    <input id="start" type="text" class="datepicker" name="start">
                    <label for="start">Start date</label>
                </div>
                <div class="input-field col s6 m6">
                    <input id="end" type="text" class="datepicker" name="end">
                    <label for="end">End date</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6">
                    <input readonly type="text" id="price" name="price"
                        value="$<?php echo $_SESSION['locker_price']; ?>">
                    <label for="price">Locker Price Per Year</label>
                </div>
            </div>
    </div>
    <div class="center">
        <a href="index.php" class="btn btn-flat">Cancel</a>
        <button type="submit" name="book" class="btn green">Book now</button>
    </div>
    </form>
    </div>
</section>
<?php
  include 'footer.php';
?>
