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
        <!-- <div class="col s12 m3">
          <div class="card center">
            <div class="row">
              <div class="col s6 icon">
                <i class="fas fa-check blue-text"></i>
              </div>
              <div class="col s6 grey-text text-darken-1">
                <h4>
                  <?php
                    $sql = "SELECT COUNT(record_sub) as sub FROM record WHERE record_sub='active'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($result);
                    echo $row['sub'];
                  ?>
                </h4>
                <p>Active users</p>
              </div>
            </div>
          </div>
        </div> -->
        <!-- <div class="col s12 m3">
          <div class="card center">
            <div class="row">
              <div class="col s6 icon">
                <i class="fas fa-box blue-text"></i>
              </div>
              <div class="col s6 grey-text text-darken-1">
                <h4>
                  <?php
                    $sql = "SELECT COUNT(locker_id) as total FROM locker";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($result);
                    echo $row['total'];
                  ?>
                </h4>
                <p>Lockers</p>
              </div>
            </div>
          </div>
        </div> -->
      </div>

      <div class="row">
        <div class="col s12 m9">
          <!-- <h5>Quicklinks</h5> -->
          <div class="row">
            <!-- <div class="col s12 m4">
              <a href="records.php">
                <div class="card center">
                  <div class="row">
                    <div class="col s6 icon">
                      <i class="fas fa-book blue-text"></i>
                    </div>
                    <div class="col s6 grey-text text-darken-1">
                      <h4>
                        <?php
                          $sql = "SELECT COUNT(record_id) as total FROM record";
                          $result = mysqli_query($conn, $sql);
                          $row = mysqli_fetch_array($result);
                          echo $row['total'];
                        ?>
                      </h4>
                      <p>Records</p>
                    </div>
                  </div>
                </div>
              </a>
            </div> -->
            <!-- <div class="col s12 m4">
              <a href="locker.php">
                <div class="card center">
                  <div class="row">
                    <div class="col s6 icon">
                      <i class="fas fa-box blue-text"></i>
                    </div>
                    <div class="col s6 grey-text text-darken-1">
                      <h4>
                        <?php
                          $sql = "SELECT COUNT(locker_id) as total FROM locker";
                          $result = mysqli_query($conn, $sql);
                          $row = mysqli_fetch_array($result);
                          echo $row['total'];
                        ?>
                      </h4>
                      <p>Lockers</p>
                    </div>
                  </div>
                </div>
              </a>
            </div> -->
            <!-- <div class="col s12 m4">
              <a href="users.php">
                <div class="card center">
                  <div class="row">
                    <div class="col s6 icon">
                      <i class="fas fa-users blue-text"></i>
                    </div>
                    <div class="col s6 grey-text text-darken-1">
                      <h4>
                        <?php
                          $sql = "SELECT COUNT(student_email) as total FROM student";
                          $result = mysqli_query($conn, $sql);
                          $row = mysqli_fetch_array($result);
                          echo $row['total'];
                        ?>
                      </h4>
                      <p>Students</p>
                    </div>
                  </div>
                </div>
              </a>
            </div> -->
            <!-- <div class="col s12 m4">
              <a href="admin.php">
                <div class="card center">
                  <div class="row">
                    <div class="col s6 icon">
                      <i class="fas fa-user blue-text"></i>
                    </div>
                    <div class="col s6 grey-text text-darken-1">
                      <h4>
                        <?php
                          $sql = "SELECT COUNT(admin_id) as total FROM admin";
                          $result = mysqli_query($conn, $sql);
                          $row = mysqli_fetch_array($result);
                          echo $row['total'];
                        ?>
                      </h4>
                      <p>Admin</p>
                    </div>
                  </div>
                </div>
              </a>
            </div> -->
            <!-- <div class="col s12 m8">
              <a href="report.php">
                <div class="card center">
                  <div class="row">
                    <div class="col s6 icon">
                      <i class="fas fa-chart-line blue-text"></i>
                    </div>
                    <div class="col s6 grey-text text-darken-1">
                      <h4>
                        <?php
                          $sql = "SELECT COUNT(record_id) as total FROM record";
                          $result = mysqli_query($conn, $sql);
                          $row = mysqli_fetch_array($result);
                          echo $row['total'];
                        ?>
                      </h4>
                      <p>Lockers</p>
                    </div>
                  </div>
                </div>
              </a>
            </div> -->
          </div>
        </div>
        <!-- <div class="col s12 m3">
          Sales analytics
          <ul class="collection with-header z-depth-1">
            <li class="collection-header blue white-text">
              <i class="fas fa-box"></i> Lockers Status
            </li>
            <?php
              // Total
              $sql = "SELECT COUNT(locker_id) as total from `locker`";
              $result = mysqli_query($conn, $sql);
              $row = mysqli_fetch_array($result);
              echo "<li class='collection-item'>Total: <span class='secondary-content'>".$row['total']."</span></li>";

              // Available
              $sql = "SELECT COUNT(locker_status) as available from `locker` WHERE locker_status='Available'";
              $result = mysqli_query($conn, $sql);
              $row = mysqli_fetch_array($result);
              echo "<li class='collection-item'>Available: <span class='secondary-content green-text'>".$row['available']."</span></li>";

              // Booked
              $sql = "SELECT COUNT(locker_status) as booked from `locker` WHERE locker_status='Booked'";
              $result = mysqli_query($conn, $sql);
              $row = mysqli_fetch_array($result);
              echo "<li class='collection-item'>Booked: <span class='secondary-content'>".$row['booked']."</span></li>";

              // Damaged
              $sql = "SELECT COUNT(locker_status) as damage from `locker` WHERE locker_status='Damaged'";
              $result = mysqli_query($conn, $sql);
              $row = mysqli_fetch_array($result);
              echo "<li class='collection-item'>Damaged: <span class='secondary-content red-text'>".$row['damage']."</span></li>";
            ?>
          </ul>

          <ul class="collection with-header z-depth-1">
            <li class="collection-header blue white-text">
              <i class="fas fa-chart-line"></i> Sales status
            </li>
            <?php
              // Total student
              $sql = "SELECT COUNT(student_email) as total from `student`";
              $result = mysqli_query($conn, $sql);
              $row = mysqli_fetch_array($result);
              echo "<li class='collection-item'>Total student: <span class='secondary-content green-text'>".$row['total']."</span></li>";
            ?>
          </ul>
        </div> -->
      </div>
    </div>
  </section>
</div>
<?php
  include 'footer.php';
?>
<script type="text/javascript">
  $(document).ready(function () {
    // Chart 1 - Total active user
    let ctx1 = $('#chart2');
    let myChart1 = new Chart(ctx1, {
      type: 'pie',
      data: {
        labels: ["Active", "Expired"],
        datasets: [{
          data: [ <?php
            $sql =
            "SELECT SUM(record_sub = 'active') AS active, SUM(record_sub = 'expired') AS expired FROM record";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
              echo $row['active'].
              ",".$row['expired'];
            } ?>
          ],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)'
          ],
          borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)'
          ],
          borderWidth: 1
        }]
      }
    });
  });
</script>
