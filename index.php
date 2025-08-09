<?php
  session_start();
  include 'navbar.php';
  require 'model/db.php';

  function load_sizes() {
    $result8 = mysqli_query($conn,
      "SELECT DISTINCT locker_size
      FROM `locker`
      WHERE `locker_location` = '" .$row7['name']. "'
      ORDER BY locker_size DESC
      ");

    if($result8) {
      while($row8 = mysqli_fetch_array($result8)) {

      }
    }
  }

  function load_lockers() {
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $output = '';

    $result = mysqli_query($conn,
      "SELECT DISTINCT locker_size
      FROM `locker`
      WHERE `locker_location` = '" .$row7['name']. "'
      ORDER BY locker_size DESC
      ");
    if($result) {
      while($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' .$row["locker_id"].'">'.$row["locker_id"].'</option>';
      }
    }

    return $output;
  }
?>

<style>

table {
  float: left;
}
tr, td {
  margin:0 !important;
  height:2em !important;
  padding: 0px !important;
}

.collapsible li i {
  -ms-transform: rotate(90deg); /* IE 9 */
  -webkit-transform: rotate(90deg); /* Chrome, Safari, Opera */
  transform: rotate(90deg);
}

.collapsible li.active i {
  -ms-transform: rotate(180deg); /* IE 9 */
  -webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
  transform: rotate(180deg);
}

</style>

<main>
  <div class="row container">
    <div class="col l3 m12 s12">

      <!--INFO-->
      <ul class="collection with-header z-depth-0">
        <li class="collection-header transparent black-text">
          <i class="fas fa-lightbulb"></i> &nbsp Info
        </li>
        <li class="collection-item">
          <p>
            Locker rentals from the University of Toronto Engineering Society
            are available every year starting in September. Rentals are on a
            first-come first-serve basis.
          </p>
          <p>
            <b>Please Note:</b> Engineering buildings are currently only accessible between 8-6 Monday-Friday. Please take this into consideration when determining whether purchasing a locker is right for you.
          </p>
          <p >
            
            For any questions or concerns, please contact the Commuter Student Director at
            <a href="mailto:commuter@skule.ca"> commuter@skule.ca </a>
          </p>
          <?php if(isset($_SESSION['s_email'])) echo("<!--"); ?>
          <p>To book a locker, <a href="register.php">register</a> or <a href="login.php">login</a>. 
          </p>
          <?php if(isset($_SESSION['s_email'])) echo("-->"); ?>
        </li>
      </ul>

      <ul class="collapsible">
        <li>
          <div class="collapsible-header valign-wrapper transparent">
              &nbsp<i class="fa fa-dollar-sign"></i> &nbsp Term Fee
              <i class="material-icons">expand_less</i>
          </div>

          <div class="collapsible-body white">
            Locker are rented by term. Please refer to the specific buildings for pictures.
              <table class="left striped centered">
                <thead>
                  <tr>
                    <th>Size</th>
                    <th>Fee</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Large</td>
                    <td>$100</td>
                  </tr>
                    <td>Medium</td>
                    <td>$60</td>
                  </tr>
                    <td>Medium - Rosebrugh</td>
                    <td>$25</td>
                  </tr>
                    <td>Small</td>
                    <td>$35</td>
                  </tr>
                    <td>X-Small</td>
                    <td>$30</td>
                  </tr>
                </tbody>
              </table>
            <p>
              <b>Note</b>: Large = Full Size,
              Medium = 1/2 Size, Small = 1/4 Size, X-Small = 1/6 Size.
            </p>
          </div>
        </li>
      </ul>
    </div>

    <!-- ENGSOC LOCKERS -->
    <div class="col l9 m12 s12">
      <h5>EngSoc Lockers </h5>
      <div class="divider"></div>

      <ul class="collapsible z-depth-0">

        <!-- Organize lockers by building -->
        <?php
          $result7 = mysqli_query($conn, "SELECT * FROM `buildings`");
          if($result7):
            while($row7 = mysqli_fetch_array($result7)): 
              // Count Available Lockers
              $building_name = $row7['name'];
              
              $countResult = mysqli_query(
                $conn,
                "SELECT COUNT(*) as available_count
                FROM locker l
                JOIN buildings b
                ON l.locker_location = b.name
                WHERE b.name = '$building_name'
                AND l.locker_status = 'Available';"
              );
              $countRow = mysqli_fetch_assoc($countResult);

              if ($countRow['available_count'] > 0):
            ?>
              <li tabindex="0" role="button" aria-expanded="false" onclick="$(this).attr('aria-expanded', $(this).attr('aria-expanded') == 'true' ? 'false' : 'true')" class="fix-outline">
                <div class="collapsible-header">
                  <ul>
                    <li class="location"><b><?php echo $row7['name']; ?></b></li>
                    <!-- Get count of Available Lockers by size -->
                    <?php
                      // X-Small
                      $countResult = mysqli_query($conn,
                        "SELECT COUNT(locker_status) as countXS
                        FROM `locker`
                        WHERE locker_status='Available'
                          AND `locker_location` = '" .$row7['name']. "'
                          AND locker_size = 'X-Small'");
                      $row1 = mysqli_fetch_array($countResult);
                      $countXS = $row1['countXS'];

                      // Small
                      $countResult = mysqli_query($conn,
                        "SELECT COUNT(locker_status) as countS
                        FROM `locker`
                        WHERE locker_status='Available'
                          AND `locker_location` = '" .$row7['name']. "'
                          AND locker_size = 'Small'");
                      $row1 = mysqli_fetch_array($countResult);
                      $countS = $row1['countS'];

                      // Medium
                      $countResult = mysqli_query($conn,
                        "SELECT COUNT(locker_status) as countM
                        FROM `locker`
                        WHERE locker_status='Available'
                          AND `locker_location` = '" .$row7['name']. "'
                          AND locker_size = 'Medium'");
                      $row1 = mysqli_fetch_array($countResult);
                      $countM = $row1['countM'];

                      // Large
                      $countResult = mysqli_query($conn,
                        "SELECT COUNT(locker_status) as countL
                        FROM `locker`
                        WHERE locker_status='Available'
                          AND `locker_location` = '" .$row7['name']. "'
                          AND locker_size = 'Large'");
                      $row1 = mysqli_fetch_array($countResult);
                      $countL = $row1['countL'];

                      // Total
                      $total = $countXS + $countS + $countM + $countL;
                    ?>
                    <li>
                      <span style="color: #5da7a7;">Available: <?php echo $total ?></span>
                      &nbsp  &nbsp
                      XS: <?php echo $countXS ?> &nbsp; &nbsp;
                      S: <?php echo $countS ?> &nbsp;  &nbsp;
                      M: <?php echo $countM ?> &nbsp;  &nbsp;
                      L: <?php echo $countL ?>
                    </li>
                  <ul>
                </div>

                <div class="collapsible-body row flex">

                <!-- All sizes in building -->
                <div class="col l5 m5 s6">
                  <select name="size" class="size">
                    <option value="">Select Size</option>
                    <?php
                      $result8 = mysqli_query($conn,
                        "SELECT DISTINCT locker_size
                        FROM `locker`
                        WHERE `locker_location` = '" .$row7['name']. "'
                        ORDER BY locker_size DESC
                        ");
                      if($result8):
                        while($row8 = mysqli_fetch_array($result8)):
                    ?>
                      <option value="<?php echo $row8['locker_size']; ?>"><?php echo $row8['locker_size']; ?></option>
                      <?php endwhile ?>
                    <?php endif ?>
                  </select>
                  <label>Size</label>
                </div>
                <!-- Available lockers by size selected -->
                <!-- Dynamically fetched based on size, see footer.php -->
                <div class="col l5 m5 s6">
                  <select name="locker" class="locker">
                    <option value="">Select Locker</option>
                  </select>
                  <label>Available Lockers</label>
                </div>

                <?php
                  if(isset($_SESSION['s_email']))
                    echo("<a href='booking.php?id='");
                  else
                    echo("<a href='/login.php?return-to=booking.php?id='");
                ?> class="btn disabled skule-green z-depth-0 s2 m2 l2">Book</a>

                </div>
              </li>
            <?php endif ?>
          <?php endwhile ?>
        <?php endif ?>
      </ul>

      <br>

      <!-- EXTERNAL LOCKERS -->
      <h5>Discipline Club/ASSU Lockers</h5>
      <div class="divider"></div>
      <p>
        Several Engineering Discipline Clubs and the Arts and Science Student
        Union (ASSU) also provide lockers for rental. These lockers,
        outlined below, are available to students in every discipline of engineering.
      </p>

      <ul class="collapsible z-depth-0">
        <?php
          // Get all info for external lockers
          $sql = "SELECT * FROM `external_lockers`";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_array($result)):
        ?>
        <!-- Start external locker source info -->
        <li tabindex="0" role="button" class="fix-outline">
          <div class="collapsible-header">
            <?php echo $row['name']; ?>
          </div>
          <div class="collapsible-body row">
          <?php echo $row['description']; ?>
          </div>
        </li>
        <?php endwhile ?>
        <!-- End -->
      </ul>
    </div>
  </div>
</main>

<?php
  include 'footer.php';
?>
