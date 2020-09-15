<?php
  session_start();
  include 'navbar.php';
  require 'model/db.php';

  // if user already login redirect them to index page
  if (isset($_SESSION['s_email'])) {
    header("Location: index.php");
  }

  // Error message and class
  $msg = $msgClass = '';

  if (filter_has_var(INPUT_POST, 'submit')) {
    // Get form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if inputs are empty
    if (!empty($email) && !empty($password)){
      // success
      $sql = "SELECT * FROM `student` WHERE `student_email`='$email'";
      $result = mysqli_query($conn, $sql);
      $resultCheck = mysqli_num_rows($result);
      $row = mysqli_fetch_assoc($result);

      if ($resultCheck < 1) {
        // error, id not exist
        $msg = "Invalid email or password";
        $msgClass = "red";
      } else {
        // verify password hash
        $pwdCheck = password_verify($_POST['password'], $row['student_pwd']);

        if($pwdCheck == false) {
          $msg = "Invalid password";
          $msgClass = "red";
        } elseif ($pwdCheck == true) {
          $_SESSION['s_name'] = $row['student_name'];
          $_SESSION['s_email'] = $row['student_email'];
          $_SESSION['s_phone'] = $row['student_phone'];

          //After logging in, if a return-to address was set, return there
          if(isset($_GET['return-to']))
            header("Location: /".$_GET['return-to']);
          else
            header("Location: /");
        }
      }
    } else {
      // failed ouput an error
      $msg = "Please fill in all fields";
      $msgClass = "red";
    }

    mysqli_close($conn);
  }

?>

<style>
/* Finally sticky footer! Good god have mercy */
body {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
}

.site-content {
  flex: 1 0 auto;
}

</style>

<!-- Login form -->
<div class="container site-content">
  <div class="box">
    <div class="row">
      <div class="col s12 m12">
        <?php if($msg != ''): ?>
          <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
            <span class="white-text"><?php echo $msg; ?></span>
          </div>
        <?php endif ?>
        <div class="card">
          <!-- <div class="card-image">
            <img id="userimg" src="img/user.png" class="circle responsive-img">
          </div> -->
          <div class="card-content" style="padding: 40px;">
            <span class="card-title center-align">User Login</span>
            <div class="row">
              <form class="col s12" method="POST" action="#" novalidate>
                <div class="row">
                  <div class="input-field">
                    <input type="text" id="email" name="email">
                    <label for="email">Email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field">
                    <input type="password" id="password" name="password">
                    <label for="userid">Password</label>
                  </div>
                </div>
                <div class="row">
                  <p class="center-align">
                    New user? <a href="register.php<?php
                      if(isset($_GET['return-to']))
                        echo("?return-to=".$_GET['return-to']);
                      ?>">Register</a><br>
                    Admin? <a href="admin/">Login</a><br><br>
                    Forgot your password? <br><a href="mailto:president@skule.ca">
                      Contact president@skule.ca</a><br><br>
                    <button type="submit" class="waves-effect waves-light btn blue" name="submit">Login</button>
                  </p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end login form -->

<?php
  include 'footer.php';
?>
