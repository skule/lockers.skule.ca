<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Manage Lockers";
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';
  $onlyAvailable = isset($_GET['onlyAvailable']) && $_GET['onlyAvailable'] == '1';

  // Form handling
  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Check if the input is empty
    if (!empty($id) && !empty($status) && !empty($price)) {
      // pass
      $sql = "INSERT INTO `locker` (`locker_id`, `locker_status`, `locker_price`)
      VALUES ('$id', '$status', '$price')";

      if (mysqli_query($conn, $sql)) {
        // Success
        $msg = "Locker added";
        $msgClass = "green";
      } else {
        $msg = "Fail to add locker error: " . $sql . "<br>" . mysqli_error($conn);
        $msgClass = "red";
      }
    } else {
      // failed
      $msg = "Please fill in all fields";
      $msgClass = "red";
    }
  }

  // Delete form handling
  if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM `locker` WHERE `locker_id`='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Delete Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error deleting this locker";
      $msgClass = "red";
    }
  }
?>

<script>
  function toggleAvailable() {
    const checked = document.getElementById('availableToggle').checked;
    const url = new URL(window.location.href);
    
    if (checked) {
      url.searchParams.set('onlyAvailable', '1');
    } else {
      url.searchParams.delete('onlyAvailable');
    }
    
    window.location.href = url.toString();
  }
</script>

<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5>Lockers</h5>
      <div class="divider"></div>
      <br>
      <div class="row">
        <div class="col s12 m6">
          <a href="#addlocker" class="btn green modal-trigger">Add New locker</a>
        </div>
        <div class="col s12 m6 right">
          <a href="locker_bulk.php" class="btn green modal-trigger right">Bulk Import Lockers</a>
        </div>
      </div>

      <!-- Filters -->
      <div class="row valign-wrapper">
        <!-- Archive toggle on the left -->
        <div class="col s12 m6 l6 left-align flow-text">
          <label>
            <input type="checkbox" id="availableToggle" <?php echo $onlyAvailable ? 'checked' : ''; ?> onchange="toggleAvailable()">
            <span><?php echo $onlyAvailable ? 'Show All Status' : 'Show Only Available'; ?></span>
          </label>
        </div>

        <!-- Search field on the right -->
        <div class="col s12 m6 l6 right-align">
          <div class="input-field">
            <i class="material-icons prefix">search</i>
            <input type="text" id="search">
            <label for="search">Search</label>
          </div>
        </div>
      </div>
      <!-- Locker table list -->
      <table id="myTable" class="table highlight centered">
        <thead class="blue darken-2 white-text">
          <tr class="myHead">
            <th>Locker id</th>
            <th>Locker Location</th>
            <th>Status</th>
            <th>Price</th>
            <!-- <th>Owner</th> -->
            <th colspan="2">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT * FROM `locker`";
            if ($onlyAvailable) $sql .= " WHERE locker_status = 'Available'";
            $sql .= " ORDER BY locker_price";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
            <tr>
              <td><?php echo $row['locker_id']; ?></td>
              <td><?php echo $row['locker_location']; ?></td>
              <td><?php echo $row['locker_status']; ?></td>
              <td><?php echo $row['locker_price']; ?></td>
              <td>
                  <a href="locker_edit.php?id=<?php echo $row['locker_id']; ?>" class='blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
              </td>
              <td>
                <form method='POST' action='locker.php'>
                  <input type='hidden' name='id' value="<?php echo $row['locker_id']; ?>">
                  <button type='submit' onclick='return confirm(`Delete this locker "<?php echo $row['locker_id']; ?>" ?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
                    <i class='far fa-trash-alt'></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile ?>
        </tbody>
      </table>

      <!-- Modal -->
      <!-- Add locker modal -->
      <div id="addlocker" class="modal">
        <div class="modal-content">
          <h5>Add Locker</h5>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="row">
              <div class="input-field col s12">
                <input id="id" type="text" name="id">
                <label for="id">Locker Id</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <select name="status">
                  <option value="" disabled selected>Choose your option</option>
                  <option value="Available">Available</option>
                  <option value="Booked">Booked</option>
                  <option value="Damage">Damage</option>
                </select>
                <label>Status</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="price" type="text" name="price">
                <label for="price">Price</label>
              </div>
            </div>
            <button type="submit" class="btn blue" name="submit">Add</button>
          </form>
        </div>
        <div class="modal-footer">
          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
      </div>
    </div>
  </section>
</div>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
