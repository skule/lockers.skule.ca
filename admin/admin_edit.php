<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Manage Admins";
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  // Get request base on admin id
  if (isset($_REQUEST['id'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $sql = "SELECT * FROM `admin` WHERE `admin_id`='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
  }

  // Check for submit
  if (filter_has_var(INPUT_POST, 'submit')){
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($username) || empty($email)){
      $msg = "Please fill in all fields";
      $msgClass = "red";
    } else {
      if(!empty($password)) {
        // Hashing the password
        $hashedPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
        // Update database
        $sql = "UPDATE `admin` SET admin_id='$username', admin_username='$username',
          admin_email='$email', admin_password='$hashedPwd' WHERE admin_id='$username'";
      } else {
        // Update database
        $sql = "UPDATE `admin` SET admin_id='$username', admin_username='$username',
          admin_email='$email' WHERE admin_id='$username'";
      }

      if (mysqli_query($conn, $sql)){
        $msg = "Update Successfull";
        $msgClass = "green";

        // Get updated data for form
        $sql = "SELECT * FROM `admin` WHERE `admin_id`='$username'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
      } else {
        $msg = "Update error: " . $sql . "<br>" . mysqli_error($conn);
        $msgClass = "red";
      }
    }
  }

  mysqli_close($conn);
?>

<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5><i class="fas fa-edit"></i>
        Edit user <span class="blue-text"><?php echo $row['admin_username']; ?></span>
      </h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
        <div class="row">
          <div class="input-field">
            <i class="material-icons prefix">face</i>
            <input type="text" id="uname" name="username" value="<?php echo $row['admin_username']; ?>">
            <label for="uname">Username</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field">
            <i class="material-icons prefix">email</i>
            <input type="email" id="email" name="email" value="<?php echo $row['admin_email']; ?>">
            <label for="email">Email</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field">
            <i class="material-icons prefix">lock</i>
            <input type="password" id="password" name="password">
            <label for="userid">New password</label> <!-- TODO update for -->
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
  include 'footer.php';
?>
