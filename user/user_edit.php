<?php
  require 'session.php';
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  if (isset($_SESSION['s_id'])) {
    $id = $_SESSION['s_id'];
    $sql = "SELECT * FROM `student` WHERE `student_id`='$id'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);
  } else {
    header("Location: index.php");
  }

  // Check for submit
  if (filter_has_var(INPUT_POST, 'submit')){
    $id = $_SESSION['s_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check required fields
    if (empty($name) || empty($email)){
      $msg = "Please fill in all fields";
      $msgClass = "red";
    } else {
      // Check email
      if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
        $msg = "Please use a valid email";
        $msgClass = "red";
      } else {
        if(!empty($password)) { //If the password needs to be changed,
          // Hashing the password
          $hashedPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);

          // Update databasee
          $sql = "UPDATE `student`
            SET `student_pwd`='$hashedPwd', `student_name`='$name',
            `student_email`='$email' WHERE student_id='$id'";
        } else { //If the password does not need to be changed
          // Insert user into database
          $sql = "UPDATE `student`
            SET `student_name`='$name',
            `student_email`='$email' WHERE student_id='$id'";
        }

        if (mysqli_query($conn, $sql)){
          $msg = "Update success";
          $msgClass = "green";
          //We've updated the database, fetch the new values
          $sql = "SELECT * FROM `student` WHERE `student_id`='$id'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_array($result);
        } else {
          $msg = "Update error: " . $sql . "<br>" . mysqli_error($conn);
          $msgClass = "red";
        }
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
                      <i class="material-icons prefix">credit_card</i>
                      <input type="text" id="id" name="id" value="<?php echo $row['student_id']; ?>" disabled>
                      <label for="id">User Id</label>
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
                      <input type="email" id="email" name="email" value="<?php echo $row['student_email']; ?>">
                      <label for="email">Email</label>
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
