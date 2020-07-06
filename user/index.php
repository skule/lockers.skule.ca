<?php
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';
?>

<div class="wrapper">
  <section class="section">
    <div class="container2">
      <div class="row">

        <!-- Active -->
        <div class="col s12 m3">
          <div class="card">
            <div class="row">
              <div class="col s6 m6 grey-text">
                <?php
                  $sql = "SELECT COUNT(record_sub) as sub 
                    FROM `record` 
                    WHERE record_sub='active' 
                      AND student_id='".$_SESSION['s_id']."'";
                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($result);
                  echo "<h5>".$row['sub']."</h5>";
                ?>
                <h5>Active</h5>
              </div>
              <div class="col s6 m6 icon green-text">
                <i class="fas fa-check"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Pending -->
        <div class="col s12 m3">
          <div class="card">
            <div class="row">
              <div class="col s6 m6 grey-text">
                <?php
                  $sql = 
                    "SELECT COUNT(record_status) as status 
                    FROM `record` 
                    WHERE record_status='pending'   
                      AND student_id='".$_SESSION['s_id']."'";
                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($result);
                  echo "<h5>".$row['status']."</h5>";
                ?>
                <h5>Pending</h5>
              </div>
              <div class="col s6 m6 icon blue-text">
                <i class="fas fa-info-circle"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Expired -->
        <div class="col s12 m3">
          <div class="card">
            <div class="row">
              <div class="col s6 m6 grey-text">
                <?php
                  $sql = "SELECT COUNT(record_sub) as sub 
                    FROM `record` 
                    WHERE record_sub='expired' 
                      AND student_id='".$_SESSION['s_id']."'";
                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($result);
                  echo "<h5>".$row['sub']."</h5>";
                ?>
                <h5>Expired</h5>
              </div>
              <div class="col s6 m6 icon red-text">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Details information -->
      <ul class="collapsible">
        <li>

        <!-- Booking Status -->
          <div class="collapsible-header active darken-2 white-text spicy-green">
            &nbsp Booking Status
          </div>
          <div class="collapsible-body">
            <table class="table highlight centered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Price</th>
                  <th>Locker id</th>
                  <th>Status</th>
                  <th>Subscription</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 1;
                  $sql = "SELECT * FROM `record` WHERE student_id='".$_SESSION['s_id']."'";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_array($result)):
                ?>
                <tr>
                  <td><?php echo $i; $i++; ?></td>
                  <td><?php echo $row['record_start']; ?></td>
                  <td><?php echo $row['record_end']; ?></td>
                  <td><?php echo "$"."".$row['record_price']; ?></td>
                  <td><?php echo $row['locker_id']; ?></td>
                  <td><?php echo $row['record_status']; ?></td>
                  <td><?php echo $row['record_sub']; ?></td>
                </tr>
              <?php endwhile ?>
              </tbody>
            </table>
          </div>
        </li>
        <li>

        <!-- User Profile -->
          <div class="collapsible-header darken-2 white-text spicy-green">
            &nbsp User Profile
          </div>
          <div class="collapsible-body">
            <p><span class="grey-text">Name:</span> 
                <?php echo $_SESSION['s_name']; ?></p>
            <p><span class="grey-text">Email:</span> 
                <?php echo $_SESSION['s_email']; ?></p>
            <a href="user_edit.php?id=<?php echo $_SESSION['s_id']; ?>" 
                class="btn1"><i class="fas fa-pencil-alt"></i>&nbsp Edit / Change Password</a>
          </div>
        </li>
      </ul>

    </div>
  </section>
</div>
<?php
  include 'footer.php';
?>
