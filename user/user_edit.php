<?php
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  if (isset($_SESSION['s_email'])) {
    $email = $_SESSION['s_email'];
    $sql = "SELECT * FROM `student` WHERE `student_email`='$email'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);
  } else {
    header("Location: index.php");
  }

  // Check for submit
  if (filter_has_var(INPUT_POST, 'submit')){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check required fields
    if (empty($name) || empty($phone)){
      $msg = "Please fill in all fields";
      $msgClass = "red";
    } else {
      if(!empty($password)) { //If the password needs to be changed,
        // Hashing the password
        $hashedPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Update databasee
        $sql = "UPDATE `student`
          SET `student_pwd`='$hashedPwd', `student_name`='$name', `student_phone`='$phone' WHERE student_email='$email'";
      } else { //If the password does not need to be changed
        // Insert user into database
        $sql = "UPDATE `student`
          SET `student_name`='$name',  `student_phone`='$phone' WHERE student_email='$email'";
      }

      if (mysqli_query($conn, $sql)){
        $msg = "Update success";
        $msgClass = "green";
        //We've updated the database, fetch the new values
        $sql = "SELECT * FROM `student` WHERE `student_email`='$email'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
      } else {
        $msg = "Update error. Please contact the administrator with the following error: <code> $sql <br/>" . mysqli_error($conn)."</code>";
        $msgClass = "red";
      }

    }
  }
?>

<!-- Register form -->
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <div class="row">
        <div class="col s12 m12">
          <?php if($msg != ''): ?>
            <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
              <span class="white-text"><?php echo $msg; ?></span>
            </div>
          <?php endif ?>
          <div class="card">
            <div class="card-content">
              <span class="card-title center-align"><h5>User Profile</h5></span>
              <div class="row">
                <form class="col s12" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
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
                      <input disabled="disabled" type="email" id="email" name="fakeemail" value="<?php echo $row['student_email']; ?>">
                      <input type="hidden" name="email" value="<?php echo $row['student_email']; ?>">
                      <label for="email">Email</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field">
                      <i class="material-icons prefix">phone</i>
                      <input type="tel" id="phone" name="phone" value="<?php echo($row['student_phone']); ?>"/>
                      <label for=phone>Phone Number</phone>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field">
                      <i class="material-icons prefix">lock</i>
                      <input type="password" id="password" name="password">
                      <label for="userid">Password</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="center">
                      <button type="submit" class="waves-effect waves-light btn blue" name="submit">Update</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php
  include 'footer.php';
?>
