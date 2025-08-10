<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Manage Users";
  include 'navbar.php';
  require '../model/db.php';
  require '../functions.php';

  $msg = $msgClass = '';

  // handle the get request base on user id
  if (isset($_REQUEST['email'])) {
    $email = mysqli_real_escape_string($conn, $_REQUEST['email']);
    $sql = "SELECT * FROM `student` WHERE `student_email`='$email'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);
  }

  // Check for submit
  if (filter_has_var(INPUT_POST, 'submit')){
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (strlen($email) > 320 || filter_var($email, FILTER_VALIDATE_EMAIL) === false || !is_email_utoronto($email)){
      $msg ="Please use a valid email" . $name;
      $msgClass = "red";
    } else {
      if(!empty($password)) {
        // Hashing the password
        $hashedPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE `student` SET student_pwd='$hashedPwd',
          student_name='$name' WHERE student_email='$email'";
      } else {
        $sql = "UPDATE `student` SET student_name='$name' WHERE student_email='$email";
      }

      if (mysqli_query($conn, $sql)){
        $msg = "Update Successfull";
        $msgClass = "green";
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
      <h5><i class="fas fa-edit"></i> Edit user <span class="blue-text"><?php echo $row['student_email']; ?></span></h5>
      <div class="divider"></div><br><br>

      <!-- Locker edit form  -->
      <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
        <div class="row">
          <div class="input-field">
            <i class="material-icons prefix">face</i>
            <input type="text" id="uname" name="username" value="<?php echo $row['student_username']; ?>">
            <label for="uname">Username</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field">
            <i class="material-icons prefix">account_circle</i>
            <input type="text" id="name" name="name" value="<?php echo $row['student_name']; ?>">
            <label for="name">Full Name</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field">
            <i class="material-icons prefix">email</i>
            <input  type="email" id="email" name="email" value="<?php echo $row['student_email']; ?>">
            <label for="email">Email</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field">
            <i class="material-icons prefix">lock</i>
            <input type="password" id="password" name="password">
            <label for="userid">New password</label>
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
  // mysqli_close($conn);
  include 'footer.php';
?>
