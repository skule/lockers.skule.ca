<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Manage Admins";
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  // Form handling
  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $hashedPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the input is empty
    if (!empty($username) && !empty($email)) {
      // pass
      $sql = "INSERT INTO `admin` (`admin_username`, `admin_email`, `admin_password`)
      VALUES ('$username', '$email', '$hashedPwd')";

      if (mysqli_query($conn, $sql)) {
        // Success
        $msg = "Admin added";
        $msgClass = "green";
      } else {
        $msg = "Fail to add admin error: " . $sql . "<br>" . mysqli_error($conn);
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
    $sql = "DELETE FROM `admin` WHERE `admin_username`='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Delete Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error deleting this locker";
      $msgClass = "red";
    }
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
      <h5>Admins</h5>
      <div class="divider"></div>
      <br>
      <div class="row">
        <div class="col s12 m6">
          <a href="#addadmin" class="btn spicy-green modal-trigger z-depth-0">Add New admin</a>
        </div>
        <div class="col s12 m6">
          <div class="input-field">
            <i class="material-icons prefix">search</i>
            <input type="text" id="search">
            <label for="search">Search</label>
          </div>
        </div>
      </div>

      <!-- Admin list -->
      <table id="myTable" class="table highlight centered">
        <thead class="blue darken-2 white-text">
          <tr class="myHead">
            <th>Username</th>
            <th>Email</th>
            <th colspan="2">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT * FROM `admin`";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
            <tr>
              <td><?php echo $row['admin_username']; ?></td>
              <td><?php echo $row['admin_email']; ?></td>
              <td>
                <a href='admin_edit.php?id=<?php echo $row['admin_username']; ?>' class='btn1 blue-text tooltipped' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
              </td>
              <td>
                <form method='POST' action='admin.php'>
                  <input type='hidden' name='id' value="<?php echo $row['admin_username'];?>">
                  <button type='submit' onclick='return confirm(`Are you sure you want to delete the admin "<?php echo $row['admin_username']; ?>"?`);' name='delete' class='btn1 red-text tooltipped' data-position='top' data-tooltip='Delete'>
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
      <div id="addadmin" class="modal">
        <div class="modal-content">
          <h5>Add Admin</h5>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <!-- <div class="row">
              <div class="input-field">
                <i class="material-icons prefix">credit_card</i>
                <input id="id" type="text" name="id">
                <label for="id">Admin Id</label>
              </div>
            </div> -->
            <div class="row">
              <div class="input-field">
                <!-- <i class="material-icons prefix">face</i> -->
                <input id="username" type="text" name="username">
                <label for="username">Username</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field">
                <!-- <i class="material-icons prefix">email</i> -->
                <input type="email" id="email" name="email">
                <label for="email">Email</label>
              </div>
            </div>
            <!-- <div class="row">
              <div class="input-field">
                <i class="material-icons prefix">local_phone</i>
                <input type="text" id="phone" name="phone">
                <label for="phone">Phone</label>
              </div>
            </div> -->
            <div class="row">
              <div class="input-field">
                <!-- <i class="material-icons prefix">lock</i> -->
                <input id="password" type="password" name="password">
                <label for="password">Password</label>
              </div>
            </div>
            <div class="center">
              <button type="submit" class="btn blue" name="submit">Add</button>
            </div>
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
