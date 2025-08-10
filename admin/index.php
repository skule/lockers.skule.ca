<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Home";
  include 'navbar.php';

  //Booking start and end dates, yyyy-mm-dd
  // Determine current date
  $today = new DateTime();
  $dateString = $today->format('Y-m-d'); // e.g. "2025-08-09"

  // Determine academic year start and end
  $year = (int)$today->format("Y");
  $month = (int)$today->format("m");

  // The booking period is always from Sept 1 to May 31
  // You can book for the following academic year starting from June 1
  // i.e. after the previous booking period ends
  if ($month >= 6) {
    $BOOKING_START_DATE = "$year-09-01";
    $BOOKING_END_DATE = ($year + 1) . "-05-31";
  } else {
    $BOOKING_START_DATE = ($year - 1) . "-09-01";
    $BOOKING_END_DATE = "$year-05-31";
  }
?>
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <h5>&nbsp Overview </h5> <!--<i class="fas fa-tachometer-alt"></i>->
      <div class="divider"></div><br>

      <!-- info bar -->
      <div class="row">
        <div class="col s12 m3">
          <a href="records.php">
            <div class="card center">
              <div class="row">
                <div class="col s6 icon">
                  <i class="fa fa-solid fa-calendar"></i>
                </div>
                <div class="col s6 grey-text text-darken-1">
                  <h4>
                    <?php
                      $sql = "SELECT COUNT(*) AS upcoming
                        FROM `record` r
                        WHERE '$dateString' < record_start";
                      $result = mysqli_query($conn, $sql);
                      $row = mysqli_fetch_assoc($result);
                      echo $row['upcoming'];
                    ?>
                  </h4>
                  <p>Upcoming Records</p>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col s12 m3">
          <a href="records.php">
            <div class="card center">
              <div class="row">
                <div class="col s6 icon">
                  <i class="fa fa-lock"></i>
                </div>
                <div class="col s6 grey-text text-darken-1">
                  <h4>
                    <?php
                      $sql = "SELECT COUNT(*) AS ongoing
                        FROM `record` r
                        WHERE record_start <= '$dateString' 
                        AND '$dateString' <= record_end";
                      $result = mysqli_query($conn, $sql);
                      $row = mysqli_fetch_assoc($result);
                      echo $row['ongoing'];
                    ?>
                  </h4>
                  <p>Ongoing Records</p>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col s12 m3">
          <a href="records.php">
            <div class="card center">
              <div class="row">
                <div class="col s6 icon">
                  <i class="fas fa-check"></i>
                </div>
                <div class="col s6 grey-text text-darken-1">
                  <h4>
                    <?php
                      $sql = "SELECT COUNT(*) AS active
                        FROM `record` r
                        LEFT JOIN `record_meta` m ON r.record_id = m.record_id
                        WHERE m.is_archived IS NULL OR m.is_archived != 1";
                      $result = mysqli_query($conn, $sql);
                      $row = mysqli_fetch_assoc($result);
                      echo $row['active'];
                    ?>
                  </h4>
                  <p>Unarchived Records</p>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col s12 m3">
          <a href="locker.php">
            <div class="card center">
              <div class="row">
                <div class="col s6 icon">
                <i class="fas fa-box blue-text"></i>
                </div>
                <div class="col s6 grey-text text-darken-1">
                  <h4>
                    <?php
                      $sql = "SELECT COUNT(locker_status) as available from `locker` WHERE locker_status='Available'";
                      $result = mysqli_query($conn, $sql);
                      $row = mysqli_fetch_assoc($result);
                      echo $row['available'];
                    ?>
                  </h4>
                  <p>Available Lockers</p>
                </div>
              </div>
            </div>
          </a>
        </div>
        <div class="col s12 m3">
          <a href="locker.php">
            <div class="card center">
              <div class="row">
                <div class="col s6 icon">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="col s6 grey-text text-darken-1">
                  <h4>
                    <?php
                      $sql = "SELECT COUNT(locker_status) AS damaged FROM `locker` WHERE locker_status='Damaged'";
                      $result = mysqli_query($conn, $sql);
                      $row = mysqli_fetch_assoc($result);
                      echo $row['damaged'];
                    ?>
                  </h4>
                  <p>Damaged Lockers</p>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </section>
</div>
<?php
  include 'footer.php';
?>
